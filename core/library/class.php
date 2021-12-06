<?php

class view
{
    public $data;
    public $cats;
    public $view_dir;
    public $site_config;
    public $compile_dir;
    public $_options = array();

    public function __construct()
    {
        $this->cats = get_cache('category');
        $this->site_config = xiaocms::load_config('config');
        $this->view_dir = TEMPLATE_DIR . SYS_THEME_DIR;
        $this->compile_dir = DATA_DIR . 'tplcache' . DS . SYS_THEME_DIR;
    }

    public function assign($key, $value = null)
    {
        if (!$key) return false;
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->_options[$k] = $v;
            }
        } else {
            $this->_options[$key] = $value;
        }
        return true;
    }

    public function display($file_name = null)
    {
        if (!empty($this->_options)) {
            extract($this->_options, EXTR_PREFIX_SAME, 'data');
            $this->_options = array();
        }
        $view_file = $this->get_view_file($file_name);
        $compile_file = $this->get_compile_file($file_name);
        if ($this->is_compile($view_file, $compile_file)) {
            $view_content = $this->load_view_file($view_file);
            $this->create_compile_file($compile_file, $view_content);
        }
        include $compile_file;
    }

    protected function _include($file_name)
    {
        if (!$file_name) return false;
        $view_file = $this->get_view_file($file_name);
        $compile_file = $this->get_compile_file($file_name);
        if ($this->is_compile($view_file, $compile_file)) {
            $view_content = $this->load_view_file($view_file);
            $this->create_compile_file($compile_file, $view_content);
        }
        return $compile_file;
    }

    protected function get_view_file($file_name)
    {
        return $this->view_dir . $file_name;
    }

    protected function get_compile_file($file_name)
    {
        return $this->compile_dir . $file_name . '.cache.php';
    }

    protected function create_compile_file($compile_file, $content)
    {
        $compile_dir = dirname($compile_file);
        if (!is_dir($compile_dir)) mkdirs($compile_dir);
        file_put_contents($compile_file, $content, LOCK_EX) or exit($compile_dir . '目录没有写入权限');
    }

    protected function is_compile($view_file, $compile_file)
    {
        return (is_file($compile_file) && is_file($view_file) && (filemtime($compile_file) >= filemtime($view_file))) ? false : true;
    }

    protected function load_view_file($view_file)
    {
        if (!is_file($view_file)) exit('模板: ' . $view_file . '不存在');
        $view_content = file_get_contents($view_file);
        return $this->handle_view_file($view_content);
    }

    protected function handle_view_file($view_content)
    {
        if (!$view_content) return false;
        $regex_array = array(
            '#{xiao:template\s+(.+?)\s*}#is',
            '#{xiao:block\s+([0-9]+)}#i',
            '#{xiao:nav\s+(.+?)\s?}#i',
            '#{\/xiao:nav}#i',
            '#{xiao:list\s+(.+?)return=(.+?)\s?}#i',
            '#{xiao:list\s+(.+?)\s?}#i',
            '#{\/xiao:list}#i',
            '#{xiao:loop\s+\$(.+?)\s+\$(\w+?)\s?}#i',
            '#{xiao:loop\s+\$(.+?)\s+\$(\w+?)\s?=>\s?\$(\w+?)\s?}#i',
            '#{\/xiao:loop}#i',
            '#{xiao:if\s+(.+?)\s?}#i',
            '#{xiao:else\sif\s+(.+?)\s?}#i',
            '#{xiao:else}#i',
            '#{\/xiao:if}#i',
            '#{xiao:function.([a-z_0-9]+)\((.*)\)}#Ui',
            '#{xiao:\$(.+?)}#i',
            '#{xiao:php\s+(.+?)}#is',
            '#\?\>\s*\<\?php\s#s',
            );
        $replace_array = array(
            "<?php include \$this->_include('\\1'); ?>",
            "<?php \$this->block(\\1);?>",
            "<?php \$return = \$this->_category(\"\\1\");  if (is_array(\$return))  foreach (\$return as \$key=>\$xiao) { \$allchildids = @explode(',', \$xiao['allchildids']);    \$current = in_array(\$catid, \$allchildids);?>",
            "<?php } ?>",
            "<?php \$return_\\2 = \$this->_listdata(\"\\1 return=\\2\"); extract(\$return_\\2); if (is_array(\$return_\\2))  foreach (\$return_\\2 as \$key_\\2=>\$\\2) { ?>",
            "<?php \$return = \$this->_listdata(\"\\1\"); extract(\$return); if (is_array(\$return))  foreach (\$return as \$key=>\$xiao) { ?>",
            "<?php } ?>",
            "<?php if (is_array(\$\\1))  foreach (\$\\1 as \$\\2) { ?>",
            "<?php if (is_array(\$\\1))  foreach (\$\\1 as \$\\2=>\$\\3) { ?>",
            "<?php  } ?>",
            "<?php if (\\1) { ?>",
            "<?php } else if (\\1) { ?>",
            "<?php } else { ?>",
            "<?php } ?>",
            "<?php echo \\1(\\2); ?>",
            "<?php echo \$\\1; ?>",
            "<?php \\1 ?>",
            " ",
            );
        return preg_replace($regex_array, $replace_array, $view_content);
    }

    protected function _category($param)
    {
        $_param = explode(' ', $param);
        $param = array();
        foreach ($_param as $p) {
            $mark = strpos($p, '=');
            if ($p && $mark !== false) {
                $var = substr($p, 0, $mark);
                $val = substr($p, $mark + 1);
                if (isset($var) && $var) $param[$var] = $val;
            }
        }
        $system = array();
        if (is_array($param)) {
            foreach ($param as $key => $val) {
                if (in_array($key, array('catid', 'typeid', 'modelid', 'parentid', 'num', 'ismenu'))) {
                    $system[$key] = $val;
                }
            }
        }
        $parentid = $system['parentid'] ? $system['parentid'] : 0;
        $i = 1;
        foreach ($this->cats as $catid => $cat) {
            if ($system['num']) if ($i > $system['num']) break;
            if (!$system['ismenu']) if (!$cat['ismenu']) continue;
            if ($system['typeid']) if ($cat['typeid'] != $system['typeid']) continue;
            if ($system['modelid']) if ($cat['modelid'] != $system['modelid']) continue;
            if ($system['catid']) {
                $catids = explode(',', $system['catid']);
                if (!in_array($cat['catid'], $catids)) continue;
            } else {
                if ($cat['parentid'] != $parentid) continue;
            }
            $data[] = $cat;
            $i++;
        }
        return $data;
    }

    protected function _listdata($param)
    {
        $_param = explode(' ', $param);
        $paramarr = $system = $fields = $_fields = array();
        foreach ($_param as $p) {
            $mark = strpos($p, '=');
            if ($p && $mark !== false) {
                $var = substr($p, 0, $mark);
                $val = substr($p, $mark + 1);
                if (isset($var) && $var) $paramarr[$var] = $val;
            }
        }
        if (is_array($paramarr)) {
            foreach ($paramarr as $key => $val) {
                if (in_array($key, array('sql', 'table', 'xiaocms', 'cache', 'page', 'urlrule', 'num', 'order', 'pagesize', 'return'))) {
                    $system[$key] = $val;
                } else {
                    $fields[$key] = $val;
                    $_fields[] = $key;
                }
            }
        }
        $db = xiaocms::load_class('Model');
        if ($system['sql']) {
            $sql = substr($param, 4);
            $data = $db->query($sql)->fetchAll();
            return array('return' => $data);
        }
        $table1 = isset($system['table']) && $system['table'] ? $system['table'] : 'content';
        $from = 'FROM ' . '#xiaocms_' . $table1;
        $table1_all_fields = $db->setTableName($table1)->getTableFields();
        $table1_fields = array_intersect($_fields, $table1_all_fields);
        if (!empty($system['xiaocms'])) {
            if ($table1 == 'content') {
                if (!empty($fields['catid']) && $this->cats[$fields['catid']]) {
                    $table2 = $this->cats[$fields['catid']]['tablename'];
                } elseif (!empty($fields['modelid'])) {
                    $content_model = get_cache('content_model');
                    $table2 = $content_model[$fields['modelid']]['tablename'];
                }
            } elseif ($table1 == 'member' && isset($fields['modelid']) && $fields['modelid']) {
                $member_model = get_cache('member_model');
                $table2 = $member_model[$fields['modelid']]['tablename'];
            }
            if ($table2) {
                $table2_all_fields = $db->setTableName($table2)->getTableFields();
                $table2_fields = array_intersect($_fields, $table2_all_fields);
                $table2_fields = array_diff($table2_fields, $table1_fields);
                $table2 = '#xiaocms_' . $table2;
                $from .= ' LEFT JOIN ' . $table2 . ' ON `#xiaocms_' . $table1 . '`.`id`=`' . $table2 . '`.`id`';
            }
        }
        $table1 = '#xiaocms_' . $table1;
        $where = '';
        $fieldsAll = array($table1 => $table1_fields, $table2 => $table2_fields);
        foreach ($fieldsAll as $_tablename => $tablename) {
            if (is_array($tablename)) {
                foreach ($tablename as $field) {
                    if ($fields[$field] == '') continue;
                    if ($field == 'catid' && !empty($fields['catid'])) {
                        if (!empty($this->cats[$fields['catid']]['child'])) {
                            $where .= ' AND `' . $_tablename . '`.`catid` IN (' . $this->cats[$fields['catid']]['allchildids'] . ')';
                        } elseif (strpos($fields['catid'], ',') !== false) {
                            $where .= ' AND `' . $_tablename . '`.`catid` IN (' . $fields['catid'] . ')';
                        } else {
                            $where .= ' AND `' . $_tablename . '`.`catid`=' . $fields['catid'];
                        }
                    } elseif ($field == 'id' && !empty($fields['id'])) {
                        $where .= ' AND `' . $_tablename . '`.`id` IN (' . $fields['id'] . ')';
                    } elseif ($field == 'thumb' && !empty($fields['thumb'])) {
                        $where .= $fields['thumb'] ? ' AND `' . $_tablename . '`.`thumb`<>""' : '';
                    } else {
                       
                        if (substr($fields[$field], 0, 1) == '(' && substr($fields[$field], -1, 1) == ')') {
                            $value       = substr($fields[$field],1,strlen($fields[$field])-2);
                            list($v1, $v2) = explode('-', $value);
                            $v1     = is_numeric($v1) ? $v1 : '"' . addslashes($v1) . '"';
                            $v2     = is_numeric($v2) ? $v2 : '"' . addslashes($v2) . '"';
                            $where .= ' AND `' . $_tablename . '`.`' . $field . '` BETWEEN ' . $v1 . ' AND ' . $v2;
                        } /* 
                            改为() 做为范围，此段代码保留，当()条件有冲突的时候可自行启用而注释上面的代码
                            elseif (substr($fields[$field], 0, 8) == 'BETWEEN_') {
                            $value       = substr($fields[$field], 8);
                            list($v1, $v2) = explode('-', $value);
                            $v1     = is_numeric($v1) ? $v1 : '"' . addslashes($v1) . '"';
                            $v2     = is_numeric($v2) ? $v2 : '"' . addslashes($v2) . '"';
                            $where .= ' AND `' . $_tablename . '`.`' . $field . '` BETWEEN ' . $v1 . ' AND ' . $v2;
                        }*/elseif (substr($fields[$field], 0, 1) == '%' || substr($fields[$field], -1, 1) == '%') {
                            $where .= ' AND `' . $_tablename . '`.`' . $field . '` LIKE \'' . $fields[$field] . '\'';
                        } /*
                            此段代码保留，当%条件前后有冲突的时候可自行启用而注释上面的代码
                            elseif (substr($fields[$field], 0, 5) == 'LIKE_') {
                            $value       = substr($fields[$field], 5);
                            $where .= ' AND `' . $_tablename . '`.`' . $field . '` LIKE \'' . $value. '\'';
                        }*/elseif (strpos($fields[$field], ',') !== false) {
                            $where .= ' AND `' . $_tablename . '`.`' . $field . '` IN (' . $fields[$field] . ')';
                        } else {
                            $value = is_numeric($fields[$field]) ? $fields[$field] : '"' . $fields[$field] . '"';
                            $where .= ' AND `' . $_tablename . '`.`' . $field . '`=' . $value . '';
                        }
                        
                    }
                }
            }
        }

        if ($table1 == '#xiaocms_content' && !isset($fields['status'])) {
            $where .= ' AND `#xiaocms_content`.`status`!=0';
        }
        if ($where) {
            if (substr($where, 0, 4) == ' AND') {
                $where = ' WHERE' . substr($where, 4);
            } else {
                $where = ' WHERE' . $where;
            }
        }
        $order = '';
        if ($system['order']) {
            if ($system['order'] == 'rand()') {
                $order .= ' ORDER BY RAND()';
            } else {
                $orderarr = explode(',', $system['order']);
                foreach ($orderarr as $t) {
                    list($_field, $_order) = explode('_', $t);
                    $_orderby = isset($_order) && strtoupper($_order) == 'ASC' ? 'ASC' : 'DESC';
                    if (in_array($_field, $table1_all_fields)) {
                        $order .= ' `' . $table1 . '`.`' . $_field . '` ' . $_orderby . ',';
                    } elseif (isset($table2_all_fields) && in_array($_field, $table2_all_fields)) {
                        $order .= ' `' . $table2 . '`.`' . $_field . '` ' . $_orderby . ',';
                    }
                }
                if ($order) {
                    $order = ' ORDER BY' . substr($order, 0, -1);
                }
            }
        } elseif ($table1 == '#xiaocms_content') {
            $order = ' ORDER BY `listorder` DESC ,`time` DESC';
        }
        $limit = '';
        if (!empty($system['num'])) {
            $limit = ' LIMIT ' . $system['num'];
        } else if (isset($system['page'])) {
            if (isset($system['urlrule'])) {
                $pageurl = $system['urlrule'];
                $pagesize = $system['pagesize'] ? $system['pagesize'] : 10;
            } elseif ($this->cats[$fields['catid']]) {
                $pageurl = self::get_category_url($this->cats[$fields['catid']], 1);
                $pagesize = $system['pagesize'] ? $system['pagesize'] : $this->cats[$fields['catid']]['pagesize'];
            } else {
                $pagesize = $system['pagesize'] ? $system['pagesize'] : 10;
                $pageurl = (!$_SERVER['QUERY_STRING']) ? $_SERVER['REQUEST_URI'] . ((substr($_SERVER['REQUEST_URI'], -1) == '?') ? 'page=[page]' : '?page=[page]') : '';
                if (!$pageurl && (stristr($_SERVER['QUERY_STRING'], 'page='))) {                                
                    $pageurl = str_ireplace('page=' . $system['page'], '', $_SERVER['REQUEST_URI']);
                    $urllast = substr($pageurl, -1);            
                    if ($urllast == '?' || $urllast == '&')
                        $pageurl .= 'page=[page]';
                    else
                        $pageurl .= '&page=[page]';
                }
                if (!$pageurl) $pageurl = $_SERVER['REQUEST_URI'] . '&page=[page]';
            }
            if (!empty($system['cache'])) {
                $sqlcache = DATA_DIR . 'models' . DS . md5($from . $where) . '.sqlcache.php';
                if (is_file($sqlcache) && time() - filemtime($sqlcache) < $system['cache'] * 60) {
                    $count = unserialize(file_get_contents($sqlcache));
                } else {
                    $count = $db->query('SELECT count(*) AS total ' . $from . ' ' . $where)->fetchAll();
                    file_put_contents($sqlcache, serialize($count), LOCK_EX);
                }
            } else {
                $count = $db->query('SELECT count(*) AS total ' . $from . ' ' . $where)->fetchAll();
            }
            $limit = ' LIMIT ' . $pagesize * ($system['page'] - 1) . ',' . $pagesize;
            $pagelist = xiaocms::load_class('Pager');
            $pagelist = $pagelist->total($count['0']['total'])->url($pageurl)->num($pagesize)->hide()->page($system['page'])->output();
        }
        if (!empty($system['cache'])) {
            $sqlcache = DATA_DIR . 'models' . DS . md5($from . $where . $order . $limit) . '.sqlcache.php';
            if (is_file($sqlcache) && time() - filemtime($sqlcache) < $system['cache'] * 60) {
                $data = unserialize(file_get_contents($sqlcache));
            } else {
                $data = $db->query('SELECT * ' . $from . $where . $order . $limit)->fetchAll();
                file_put_contents($sqlcache, serialize($data), LOCK_EX);
            }
        } else {
            $data = $db->query('SELECT * ' . $from . $where . $order . $limit)->fetchAll();
        }
        if (isset($system['return']) && $system['return']) {
            return array(
                'pagelist_' . $system['return'] => $pagelist,
                'return_' . $system['return'] => $data,
                );
        }
        foreach ($data as $key => $t) {
            $data[$key]['url'] = self::get_show_url($t);
        }
        return array('pagelist' => $pagelist, 'return' => $data,);
    }

    protected function block($id)
    {
        $data = get_cache('block');
        $row = $data[$id];
        if (empty($row)) return null;
        echo htmlspecialchars_decode($row['content']);
    }


    public function get_category_url($data, $page = 0)
    {
        if ($data['typeid'] == 3) return $data['http'];
        if (!empty($this->site_config['diy_url']) && $this->site_config['list_url']) {
            $data['page'] = '[page]';
            if(version_compare(PHP_VERSION,'5.3.0')<0 ){
                if (!empty($page)){
                    $url = preg_replace('#{([a-z_0-9]+)}#e', '\$data[\\1]', $this->site_config['list_page_url']);
                } else {
                    $url = preg_replace('#{([a-z_0-9]+)}#e', '\$data[\\1]', $this->site_config['list_url']);
                }
            } else {
                $this->data=$data;
                if (!empty($page)){
                    $url = preg_replace_callback("#{([a-z_0-9]+)}#", array(__CLASS__, '_replace'), $this->site_config['list_page_url']);
                }
                else {
                    $url =  preg_replace_callback("#{([a-z_0-9]+)}#", array(__CLASS__, '_replace'), $this->site_config['list_url']);
                }
            }
        }
        else {
            $url = 'index.php?catid=' . $data['catid'];
            if (!empty($page)) $url = 'index.php?catid=' . $data['catid'] . '&page=[page]';
        }
        return SITE_PATH . $url;
    }

    public function get_show_url($data, $page = 0)
    {
        if (!empty($this->site_config['diy_url']) && $this->site_config['show_url']) {
            $data['catdir'] = $this->cats[$data['catid']]['catdir'];
            $data['catid'] = $this->cats[$data['catid']]['catid'];
            $data['page'] = '[page]';
            if(version_compare(PHP_VERSION,'5.3.0')<0 ){
                if (!empty($page)){
                    $url = preg_replace('#{([a-z_0-9]+)}#e', '\$data[\\1]', $this->site_config['show_page_url']);
                } else {
                    $url = preg_replace('#{([a-z_0-9]+)}#e', '\$data[\\1]', $this->site_config['show_url']);
                }
            } else {
                $this->data=$data;
                if (!empty($page)){
                    $url = preg_replace_callback("#{([a-z_0-9]+)}#", array(__CLASS__, '_replace'), $this->site_config['show_page_url']);
                }
                else {
                    $url =  preg_replace_callback("#{([a-z_0-9]+)}#", array(__CLASS__, '_replace'), $this->site_config['show_url']);
                }
            }
        } else {
            $url = 'index.php?id=' . $data['id'];
            if (!empty($page)) $url = 'index.php?id=' . $data['id'] . '&page=[page]';
        }
        return SITE_PATH . $url;
    }
    
    public function _replace($r)
    {
        return $this->data[$r[1]];
    }
    
    public function __destruct()
    {
        $this->_options = array();
    }

}

class Model
{

    protected $_dbName = null;
    protected $_tableName = null;
    protected $_tableField = array();
    protected $_primaryKey = null;
    protected $_prefix = null;
    protected $_errorInfo = null;
    protected $_parts = array();
    protected $_db = null;

    public function __construct()
    {
        $params = xiaocms::load_config('database');
        if (!is_array($params)) exit('数据库配置文件不存在');
        foreach ($params as $key => $value) {
            $params[$key] = trim($value);
        }
        $dsn_array = array();
        $dsn_array['host'] = $params['host'];
        $dsn_array['port'] = $params['port'];
        $dsn_array['dbname'] = $params['dbname'];
        $dsn_array['charset'] =$params['charset'];
        $params['dsn'] = sprintf('%s:%s', 'mysql', http_build_query($dsn_array, '', ';'));
        $this->_dbName = $params['dbname'];
        $this->_prefix = (isset($params['prefix']) && $params['prefix']) ? $params['prefix'] : '';
        $this->_db = DBpdo::getInstance($params);
        unset($params['username']);
        unset($params['password']);
        return true;
    }

    public function getServerVersion()
    {
        return $this->_db->getServerVersion();
    }

    public function getTableList()
    {
        return $this->_db->getTableList();
    }

    public function getdbName()
    {
        return $this->_dbName;
    }

    public function getTablePrefix()
    {
        return $this->_prefix;
    }

    public function execute($sql, $params = null)
    {
        if (!$sql) return false;
        $sql = str_replace('#xiaocms_', $this->_prefix, $sql);
        return $this->_db->execute($sql, $params);
    }

    public function query($sql, $params = null)
    {
        if (!$sql) return false;
        $sql = str_replace('#xiaocms_', $this->_prefix, $sql);
        return $this->_db->query($sql, $params);
    }

    public function fetchAll($model = 'PDO::FETCH_ASSOC')
    {
        if (!$model) return false;
        return $this->_db->fetchAll($model);
    }

    public function insert($data, $returnId = false)
    {
        if (!$data || !is_array($data)) return false;
        $insertArray = $this->_filterFields($data);
        if (!$insertArray) return false;
        unset($data);
        return $this->_db->insert($this->_tableName, $insertArray, $returnId);
    }

    public function setTableName($tableName)
    {
        if (!$tableName) return false;
        $this->_tableName = $this->_prefix . trim($tableName);
        return $this;
    }

    protected function _filterFields($data)
    {
        if (!$data || !is_array($data)) return false;
        $tableFields = $this->getTableFields();
        $filteredArray = array();
        foreach ($data as $key => $value) {
            if (in_array($key, $tableFields)) {
                $filteredArray[$key] = $value;
            }
        }
        return $filteredArray;
    }

    public function getTableFields()
    {
        if (!$this->_loadCache($this->_tableName)) {
            $this->_createCache($this->_tableName);
        }
        return $this->_tableField;
    }

    protected function _loadCache($tableName)
    {
        if (!$tableName) return false;
        $cacheFile = $this->_getCacheFile($tableName);
        if (!is_file($cacheFile)) return false;
        $cachContent = include $cacheFile;
        $this->_primaryKey = $cachContent['primaryKey'];
        $this->_tableField = $cachContent['fields'];
        unset($cachContent);
        return true;
    }

    protected function _getCacheFile($tableName)
    {
        $cachePath = DATA_DIR . 'models' . DS;
        return $cachePath . $tableName . '.tableinfo.cache.php';
    }

    protected function _createCache($tableName)
    {
        if (!$tableName) return false;
        $tableInfo = $this->_db->getTableInfo($tableName);
        $this->_primaryKey = $tableInfo['primaryKey'][0];
        $this->_tableField = $tableInfo['fields'];
        $cacheDataArray = array(
            'primaryKey' => $this->_primaryKey,
            'fields' => $this->_tableField,
            );
        $cacheContent = "<?php\nif (!defined('IN_XIAOCMS')) exit();\nreturn " . var_export($cacheDataArray, true) . ";";
        $cacheFile = $this->_getCacheFile($tableName);
        $cacehDir = dirname($cacheFile);
        if (!is_dir($cacehDir)) mkdirs($cacehDir);
        file_put_contents($cacheFile, $cacheContent, LOCK_EX);
        return true;
    }

    public function update($data, $where = null, $value = null)
    {
        if (!is_array($data) || !$data) return false;
        $condition = $this->_parseCondition($where, $value);
        if (!$condition['where']) return false;
        $condition['where'] = ltrim($condition['where'], 'WHERE ');
        $updateArray = $this->_filterFields($data);
        unset($data);
        return $this->_db->update($this->_tableName, $updateArray, $condition['where'], $condition['value']);
    }

    protected function _parseCondition($where = null, $value = null)
    {
        $conditionArray = array('where' => null, 'value' => null);
        if (!$where) {
            if (isset($this->_parts['where']) && $this->_parts['where']) {
                $conditionArray['where'] = $this->_parts['where'];
                unset($this->_parts['where']);
            }
            if (isset($this->_parts['whereValue']) && $this->_parts['whereValue']) {
                $conditionArray['value'] = $this->_parts['whereValue'];
                unset($this->_parts['whereValue']);
            }
            return $conditionArray;
        } else {
            if (isset($this->_parts['where'])) {
                unset($this->_parts['where']);
            }
            if (isset($this->_parts['whereValue'])) {
                unset($this->_parts['whereValue']);
            }
        }
        if (is_array($where)) {
            $where = implode(' AND ', $where);
        }
        $conditionArray['where'] = 'WHERE ' . $where;
        if (!is_null($value)) {
            if (!is_array($value)) {
                $value = array($value);
            }
            $conditionArray['value'] = $value;
        }
        return $conditionArray;
    }

    public function delete($where = null, $value = null)
    {
        $condition = $this->_parseCondition($where, $value);
        if (!$condition['where']) return false;
        $condition['where'] = ltrim($condition['where'], 'WHERE ');
        return $this->_db->delete($this->_tableName, $condition['where'], $condition['value']);
    }

    public function find($id, $fields = null)
    {
        if (!$id) return false;
        $fields = $this->_parseFields($fields);
        $primaryKey = $this->_getPrimaryKey();
        $sql = "SELECT {$fields} FROM {$this->_tableName} WHERE {$primaryKey} = ?";
        $myRow = $this->_db->getOne($sql, $id);
        return $myRow;
    }

    protected function _parseFields($fields = null)
    {
        if (!$fields) {
            if (isset($this->_parts['fields']) && $this->_parts['fields']) {
                $fields = $this->_parts['fields'];
                unset($this->_parts['fields']);
            } else {
                $fields = '*';
            }
            return $fields;
        } else {
            if (isset($this->_parts['fields'])) {
                unset($this->_parts['fields']);
            }
        }
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        return $fields;
    }

    protected function _getPrimaryKey()
    {
        if (!$this->_loadCache($this->_tableName)) {
            $this->_createCache($this->_tableName);
        }
        return $this->_primaryKey;
    }

    protected function _parseLimit($startId = null, $listNum = null)
    {
        $limitString = '';
        if (is_null($startId)) {
            if (isset($this->_parts['limit']) && $this->_parts['limit']) {
                $limitString = $this->_parts['limit'];
                unset($this->_parts['limit']);
            }
            return $limitString;
        } else {
            if (isset($this->_parts['limit'])) {
                unset($this->_parts['limit']);
            }
        }
        $limitString = "LIMIT" . (($listNum) ? "{$startId},{$listNum}" : $startId);
        return $limitString;
    }

    public function getOne($where = null, $value = null, $fields = null, $orderDesc = null)
    {
        $condition = $this->_parseCondition($where, $value);
        if (!$condition['where']) return false;
        $fields = $this->_parseFields($fields);
        $sql = "SELECT {$fields} FROM {$this->_tableName} {$condition['where']}";
        $orderString = $this->_parseOrder($orderDesc);
        if ($orderString) {
            $sql .= ' ' . $orderString;
        }
        return $this->_db->getOne($sql, $condition['value']);
    }

    protected function _parseOrder($orderDesc = null)
    {
        if (!$orderDesc) {
            if (isset($this->_parts['order']) && $this->_parts['order']) {
                $orderDesc = $this->_parts['order'];
                unset($this->_parts['order']);
            }
            return $orderDesc;
        } else {
            if (isset($this->_parts['order'])) {
                unset($this->_parts['order']);
            }
        }
        if (is_array($orderDesc)) {
            $orderDesc = implode(',', $orderDesc);
        }
        return 'ORDER BY ' . $orderDesc;
    }

    public function getAll($where = null, $value = null, $fields = null, $orderDesc = null, $limitStart = null, $listNum = null)
    {
        $condition = $this->_parseCondition($where, $value);
        $fields = $this->_parseFields($fields);
        $sql = "SELECT {$fields} FROM {$this->_tableName} {$condition['where']}";
        $orderString = $this->_parseOrder($orderDesc);
        if ($orderString) {
            $sql .= ' ' . $orderString;
        }
        $limitString = $this->_parseLimit($limitStart, $listNum);
        if ($limitString) {
            $sql .= ' ' . $limitString;
        }
        return $this->_db->getAll($sql, $condition['value']);
    }

    public function page($page = null, $pageSize = 10, $fields = null, $order = null)
    {
        //参数分析
        $page = ((int)$page < 1) ? 1 : $page;
        $startId = (int)$pageSize * ($page - 1);
        $condition = $this->_parseCondition($where, $value);
        $fields = $this->_parseFields($fields);
        $sql = "SELECT {$fields} FROM {$this->_tableName} {$condition['where']}";
        $orderString = $this->_parseOrder($order);
        if ($orderString) {
            $sql .= ' ' . $orderString;
        }

        $sql .= " LIMIT " . (($pageSize) ? "{$startId},{$pageSize}" : $startId);
        $data = array();
        $data['list'] = $this->_db->getAll($sql, $condition['value']);

        // 统计的sql
        $csql =  "SELECT COUNT(*) FROM {$this->_tableName} {$condition['where']}";
        $num = $this->_db->getOne($csql, $condition['value']);
        $data['total'] = $num ? $num['COUNT(*)'] : 0;
        return $data;
    }

    public function count($where = null, $value = null)
    {
        $condition = $this->_parseCondition($where, $value);
        $sql = "SELECT COUNT(*) AS valueName  FROM {$this->_tableName} {$condition['where']}";
        $myRow = $this->_db->getOne($sql, $condition['value']);
        return (!$myRow) ? 0 : $myRow['valueName'];
    }

    public function where($where, $value = null)
    {
        if (!$where) return false;
        if (is_array($where)) {
            $where = implode(' AND ', $where);
        }
        $this->_parts['where'] = (isset($this->_parts['where']) && $this->_parts['where']) ? $this->_parts['where'] . ' AND ' . $where : ' WHERE ' . $where;
        if (!is_null($value)) {
            if (!is_array($value)) {
                $value = func_get_args();
                array_shift($value);
            }
            if (isset($this->_parts['whereValue']) && $this->_parts['whereValue']) {
                $this->_parts['whereValue'] = array_merge($this->_parts['whereValue'], $value);
            } else {
                $this->_parts['whereValue'] = $value;
            }
        }
        return $this;
    }

    public function order($orderDesc)
    {
        if (!$orderDesc) return false;
        if (is_array($orderDesc)) {
            $orderDesc = implode(',', $orderDesc);
        }
        $this->_parts['order'] = (isset($this->_parts['order']) && $this->_parts['order']) ? $this->_parts['order'] . ', ' . $orderDesc : ' ORDER BY ' . $orderDesc;
        return $this;
    }

    public function fields($fieldName)
    {

        if (!$fieldName) return false;
        if (!is_array($fieldName)) {
            $fieldName = func_get_args();
        }
        $fieldName = implode(',', $fieldName);
        $this->_parts['fields'] = $fieldName;
        return $this;
    }

    public function pageLimit($page, $listNum)
    {
        $page = (int)$page;
        $listNum = (int)$listNum;
        if (!$listNum) return false;
        $page = ($page < 1) ? 1 : $page;
        $startId = (int)$listNum * ($page - 1);
        return $this->limit($startId, $listNum);
    }

    public function limit($limitStart, $listNum = null)
    {
        $limitStart = (int)$limitStart;
        $listNum = (int)$listNum;
        $limitStr = ($listNum) ? $limitStart . ', ' . $listNum : $limitStart;
        $this->_parts['limit'] = ' LIMIT ' . $limitStr;
        return $this;
    }

    public function __destruct()
    {
        $this->_db = null;
        $this->_parts = array();
    }

}

class DBpdo
{

    protected static $_instance = null;
    protected $_dbLink = null;
    protected $_query = null;

    public function __construct($params = array())
    {
        if (!$params['dsn']) return false;
        try {
            $flags = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';", PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION);
            $this->_dbLink = new PDO($params['dsn'], $params['username'], $params['password'], $flags);
        } catch (PDOException $e) {
            exit('提示：数据库连接错误！错误信息：'.$e->getMessage());
        }
        $version = $this->getServerVersion();
        if($version > '5.0') $this->_dbLink->exec("SET sql_mode=''");
        if(version_compare(PHP_VERSION,'5.3.6','<=')){
            $this->_dbLink->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return true;
    }

    public function query($sql, $params = array())
    {
        if (!$sql) return false;
        if (!is_array($params) && isset($params)) {
            $params = func_get_args();
            array_shift($params);
        }
        $result = $this->_execute($sql, $params);
        if (!$result) {
            $result->closeCursor();
            $this->_query = null;
            return $this;
        }
        $this->_query = $result;
        return $this;
    }

    public function execute($sql, $params = null)
    {
        if (!$sql) return false;
        $sql = trim($sql);
        if (!is_array($params) && isset($params)) {
            $params = func_get_args();
            array_shift($params);
        }
        $sth = $this->_dbLink->prepare($sql);
        if (!$params) {
            $result = $sth->execute();
        } else {
            $result = $sth->execute($params);
        }
        if (!$result) {
            $sth->closeCursor();
            return false;
        }
        return true;
    }

    public function fetchRow($model = PDO::FETCH_ASSOC)
    {
        if (!$model) return false;
        if (!$this->_query) return false;
        $myrow = $this->_query->fetch($model);
        $this->_query->closeCursor();
        $this->_query = null;
        return $myrow;
    }

    public function fetchAll($model = PDO::FETCH_ASSOC)
    {
        if (!$model) return false;
        if (!$this->_query) return false;
        $myrow = $this->_query->fetchAll($model);
        $this->_query->closeCursor();
        $this->_query = null;
        return $myrow;
    }

    public function getOne($sql, $params = array())
    {
        if (!$sql) return false;
        if (!is_array($params) && isset($params)) {
            $params = func_get_args();
            array_shift($params);
        }
        $result = $this->_execute($sql, $params);
        if (!$result) {
            $result->closeCursor();
            return false;
        }
        $myrow = $result->fetch(PDO::FETCH_ASSOC);
        $result->closeCursor();
        return $myrow;
    }

    public function getAll($sql, $params = array())
    {
        if (!$sql) return false;
        if (!is_array($params) && isset($params)) {
            $params = func_get_args();
            array_shift($params);
        }
        $result = $this->_execute($sql, $params);
        if (!$result) {
            $result->closeCursor();
            return false;
        }
        $myrow = $result->fetchAll(PDO::FETCH_ASSOC);
        $result->closeCursor();
        return $myrow;
    }

    protected function _execute($sql, $params = array())
    {
        $sql = trim($sql);
        $sth = $this->_dbLink->prepare($sql);
        if (!$params) {
            $result = $sth->execute();
        } else {
            $result = $sth->execute($params);
        }
        if (!$result) {
            $sth->closeCursor();
            return false;
        }
        return $sth;
    }

    public function lastInsertId()
    {
        return $this->_dbLink->lastInsertId();
    }

    public function getServerVersion()
    {
        return $this->_dbLink->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    public function insert($tableName, $data, $returnId = false)
    {
        if (!$tableName || !$data || !is_array($data)) return false;
        $contentArray = array_values($data);
        $fieldString = implode(',', array_keys($data));
        $contentString = rtrim(str_repeat('?,', count($contentArray)), ',');
        $sql = "INSERT INTO {$tableName} ({$fieldString}) VALUES ({$contentString})";
        $reulst = $this->execute($sql, $contentArray);
        unset($fieldString, $contentString, $contentString);
        if ($reulst && $returnId === true) {
            return $this->lastInsertId();
        }
        return $reulst;
    }

    public function update($tableName, $data, $where, $value = array())
    {
        if (!$tableName || !$where || !$data || !is_array($data)) return false;
        $fieldArray = array_keys($data);
        $contentString = implode('=?,', $fieldArray) . '=?';
        $params = array_values($data);
        if ($value) {
            if (!is_array($value)) {
                array_push($params, $value);
            } else {
                $params = array_merge($params, $value);
            }
        }
        $sql = "UPDATE {$tableName} SET {$contentString} WHERE {$where}";
        $reulst = $this->execute($sql, $params);
        unset($fieldArray, $contentString, $params);
        return $reulst;
    }

    public function delete($tableName, $where, $value = array())
    {
        if (!$tableName || !$where) return false;
        if ($value && !is_array($value)) $value = array($value);
        $sql = "DELETE FROM {$tableName} WHERE {$where}";
        return $this->execute($sql, $value);
    }

    public function getTableInfo($tableName, $extItem = false)
    {
        if (!$tableName) return false;
        $fieldList = $this->getAll("SHOW FIELDS FROM {$tableName}");
        if ($extItem === true) return $fieldList;
        $primaryArray = array();
        $fieldArray = array();
        foreach ($fieldList as $lines) {
            if ($lines['Key'] == 'PRI') {
                $primaryArray[] = $lines['Field'];
            }
            $fieldArray[] = $lines['Field'];
        }
        return array('primaryKey' => $primaryArray, 'fields' => $fieldArray);
    }

    public function getTableList()
    {
        $dbList = $this->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        if (!$dbList) return array();
        return array_values($dbList);
    }

    public function __destruct()
    {
        if (isset($this->_dbLink)) {
            $this->_dbLink = null;
        }
        return true;
    }

    public static function getInstance($params = array())
    {
        if (!self::$_instance) {
            self::$_instance = new self($params);
        }
        return self::$_instance;
    }

}

class Cookie
{

    protected static $_defaultConfig = array(
        'expire' => 86400,
        'path' => '/',
        'domain' => null
        );

    public static function get($cookieName = null, $default = null)
    {
        if (!$cookieName) {
            return isset($_COOKIE) ? $_COOKIE : null;
        }
        $cookieName = COOKIE_PRE . $cookieName;
        return isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : $default;
    }

    public static function set($name, $value, $expire = null, $path = null, $domain = null)
    {
        if (!$name) return false;
        $name = COOKIE_PRE . $name;
        $expire = is_null($expire) ? self::$_defaultConfig['expire'] : time() + $expire;
        if (is_null($path)) $path = '/';
        $expire = time() + $expire;
        setcookie($name, $value, $expire, $path, $domain);
        $_COOKIE[$name] = $value;
        return true;
    }

    public static function delete($name)
    {
        if (!$name) return false;
        self::set($name, null, '-86400');
        unset($_COOKIE[COOKIE_PRE . $name]);
        return true;
    }

    public static function clear()
    {
        if (isset($_COOKIE)) {
            unset($_COOKIE);
        }
        return true;
    }

}

class Session
{

    protected static $start = false;

    public function __construct()
    {
        $this->_setTimeout();
    }

    public static function start()
    {
        if (!self::$start) {
            $sessionPath = DATA_DIR . 'session' . DS;
            if (is_dir($sessionPath) && is_writable($sessionPath)) {
                session_save_path($sessionPath);
            }
            if (isset($_REQUEST['session_id'])){ 
                session_id($_REQUEST['session_id']); 
            }
            session_start();
            self::$start = true;
        }
    }

    public static function set($key, $value = null)
    {
        if (!self::$start) {
            self::start();
        }

        if (!$key) return false;
        $_SESSION[$key] = $value;
        return true;
    }

    public static function get($key, $default = null)
    {
        if (!self::$start) {
            self::start();
        }
        if (!$key) return isset($_SESSION) ? $_SESSION : null;
        if (!isset($_SESSION[$key])) return $default;
        return $_SESSION[$key];
    }

    public static function delete($key)
    {
        if (!self::$start) {
            self::start();
        }
        if (!$key) return false;
        if (!isset($_SESSION[$key])) return false;
        unset($_SESSION[$key]);
        return true;
    }

    public static function clear()
    {
        if (!self::$start) {
            self::start();
        }

        $_SESSION = array();
        return true;
    }

    public static function destory()
    {
        unset($_SESSION);
        session_destroy();

        return true;
    }

    public static function close()
    {
        if (self::$start === true) {
            session_write_close();
        }
        return true;
    }

    protected static function _setTimeout()
    {
        return ini_set('session.gc_maxlifetime', 21600);
    }

    public function __destruct()
    {
        $this->close();
        return true;
    }
    
}

class Tree
{

    public $arr = array();
    public $icon = array('│', '├', '└');
    public $nbsp = "&nbsp;";
    public $ret = '';

    public function init($arr = array())
    {
        $this->arr = $arr;
        $this->ret = '';
        return is_array($arr);
    }

    public function get_child($myid)
    {
        $a = $newarr = array();
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if ($a['parentid'] == $myid) $newarr[$id] = $a;
            }
        }
        return $newarr ? $newarr : false;
    }

    public function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = '')
    {
        $number = 1;
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $value) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';
                $selected = $id == $sid ? 'selected' : '';
                @extract($value);
                $parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                $nbsp = $this->nbsp;
                $this->get_tree($id, $str, $sid, $adds . $k . $nbsp, $str_group);
                $number++;
            }
        }
        return $this->ret;
    }

    public function get_tree_category($myid, $str, $str2, $sid = 0, $adds = '')
    {
        $number = 1;
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $a) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';

                $selected = $this->have($sid, $id) ? 'selected' : '';
                @extract($a);
                if (empty($html_disabled)) {
                    eval("\$nstr = \"$str\";");
                } else {
                    eval("\$nstr = \"$str2\";");
                }
                $this->ret .= $nstr;
                $this->get_tree_category($id, $str, $str2, $sid, $adds . $k . '&nbsp;');
                $number++;
            }
        }
        return $this->ret;
    }

    function get_treeview($myid, $effected_id = 'example', $str = "<span class='file'>\$name</span>", $str2 = "<span class='folder'>\$name</span>", $showlevel = 0, $style = 'filetree ', $currentlevel = 1, $recursion = FALSE)
    {
        $child = $this->get_child($myid);
        if (!defined('EFFECTED_INIT')) {
            $effected = ' id="' . $effected_id . '"';
            define('EFFECTED_INIT', 1);
        } else {
            $effected = '';
        }
        $placeholder = '<ul><li><span class="placeholder"></span></li></ul>';
        if (!$recursion) $this->str .= '<ul' . $effected . '  class="' . $style . '">';
        foreach ($child as $id => $a) {

            @extract($a);
            if ($showlevel > 0 && $showlevel == $currentlevel && $this->get_child($id)) $folder = 'hasChildren'; //如设置显示层级模式@2011.07.01
            $floder_status = isset($folder) ? ' class="' . $folder . '"' : '';
            $this->str .= $recursion ? '<ul><li' . $floder_status . ' id=\'' . $id . '\'>' : '<li' . $floder_status . ' id=\'' . $id . '\'>';
            $recursion = FALSE;
            if ($this->get_child($id)) {
                eval("\$nstr = \"$str2\";");
                $this->str .= $nstr;
                if ($showlevel == 0 || ($showlevel > 0 && $showlevel > $currentlevel)) {
                    $this->get_treeview($id, $effected_id, $str, $str2, $showlevel, $style, $currentlevel + 1, TRUE);
                } elseif ($showlevel > 0 && $showlevel == $currentlevel) {
                    $this->str .= $placeholder;
                }
            } else {
                eval("\$nstr = \"$str\";");
                $this->str .= $nstr;
            }
            $this->str .= $recursion ? '</li></ul>' : '</li>';
        }
        if (!$recursion) $this->str .= '</ul>';
        return $this->str;
    }

    private function have($list, $item)
    {
        return (strpos(',,' . $list . ',', ',' . $item . ','));
    }
    
}

class Image
{

    public function checkcode($width = 60, $height = 24, $verifyName = 'checkcode')
    {

        $code = "ABCDEFGHKLMNPRSTUVWYZ23456789";
        $length = 4;
        $randval = '';
        for ($i = 0; $i < $length; $i++) {
            $char = $code{rand(0, strlen($code) - 1)};
            $randval .= $char;
        }
        Session::set($verifyName, strtolower($randval));
        $width = ($length * 10 + 10) > $width ? $length * 10 + 10 : $width;
        $im = imagecreate($width, $height);
        $backColor = imagecolorallocate($im, 255, 255, 255);
        $borderColor = imagecolorallocate($im, 255, 255, 255);
        @imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
        @imagerectangle($im, 0, 0, $width - 1, $height - 1, $borderColor);
        $fontcolor = imagecolorallocate($im, rand(0, 200), rand(0, 120), rand(0, 120));
        for ($i = 0; $i < $length; $i++) {
            $fontsize = 5;
            $x = floor($width / $length) * $i + 5;
            $y = rand(0, $height - 15);
            imagechar($im, $fontsize, $x, $y, $randval{$i}, $fontcolor);
        }
        self::output($im, 'png');
    }

    public function thumb($image, $thumbname, $maxWidth = 200, $maxHeight = 50, $interlace = true)
    {
        $info = self::getImageInfo($image);
        if ($info !== false) {
            $srcWidth = $info['width'];
            $srcHeight = $info['height'];
            $type = strtolower($info['type']);
            $interlace = $interlace ? 1 : 0;
            unset($info);
            $scale = min($maxWidth / $srcWidth, $maxHeight / $srcHeight);
            if ($scale >= 1) {
                $width = $srcWidth;
                $height = $srcHeight;
            } else {
                $width = (int)($srcWidth * $scale);
                $height = (int)($srcHeight * $scale);
            }
            $createFun = 'ImageCreateFrom' . ($type == 'jpg' ? 'jpeg' : $type);
            $srcImg = $createFun($image);
            if ($type != 'gif' && function_exists('imagecreatetruecolor')) {
                $thumbImg = imagecreatetruecolor($width, $height);
            } else {
                $thumbImg = imagecreate($width, $height);
            }
            if (function_exists("ImageCopyResampled")) {
                imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
            } else {
                imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
            }
            if ('gif' == $type || 'png' == $type) {
                $background_color = imagecolorallocate($thumbImg, 0, 255, 0);
                imagecolortransparent($thumbImg, $background_color);
            }
            if ('jpg' == $type || 'jpeg' == $type) {
                imageinterlace($thumbImg, $interlace);
            }
            $dir = dirname($thumbname);
            if (!is_dir($dir)) mkdirs($dir);
            $imageFun = 'image' . ($type == 'jpg' ? 'jpeg' : $type);
            $imageFun($thumbImg, $thumbname);
            imagedestroy($thumbImg);
            imagedestroy($srcImg);
            return $thumbname;
        }
        return false;
    }

    static public function watermark($image, $waterPos = 5)
    {
        $water = CORE_PATH . 'img/watermark/watermark.png';
        if (!file_exists($image) || !file_exists($water)) return false;
        $imageInfo = self::getImageInfo($image);
        $image_w = $imageInfo['width'];
        $image_h = $imageInfo['height'];
        $imageFun = "imagecreatefrom" . $imageInfo['type'];
        $image_im = $imageFun($image);
        $waterInfo = self::getImageInfo($water);
        $w = $water_w = $waterInfo['width'];
        $h = $water_h = $waterInfo['height'];
        $waterFun = "imagecreatefrom" . $waterInfo['type'];
        $water_im = $waterFun($water);

        switch ($waterPos) {
            case 0:
            $posX = rand(0, ($image_w - $w));
            $posY = rand(0, ($image_h - $h));
            break;
            case 1:
            $posX = 0;
            $posY = 0;
            break;
            case 2:
            $posX = ($image_w - $w) / 2;
            $posY = 0;
            break;
            case 3:
            $posX = $image_w - $w;
            $posY = 0;
            break;
            case 4:
            $posX = 0;
            $posY = ($image_h - $h) / 2;
            break;
            case 5:
            $posX = ($image_w - $w) / 2;
            $posY = ($image_h - $h) / 2;
            break;
            case 6:
            $posX = $image_w - $w;
            $posY = ($image_h - $h) / 2;
            break;
            case 7:
            $posX = 0;
            $posY = $image_h - $h;
            break;
            case 8:
            $posX = ($image_w - $w) / 2;
            $posY = $image_h - $h;
            break;
            case 9:
            $posX = $image_w - $w;
            $posY = $image_h - $h;
            break;
            default:
            $posX = rand(0, ($image_w - $w));
            $posY = rand(0, ($image_h - $h));
            break;
        }
        imagealphablending($image_im, true);
        imagecopy($image_im, $water_im, $posX, $posY, 0, 0, $water_w, $water_h);
        $bulitImg = "image" . $imageInfo['type'];
        $bulitImg($image_im, $image);
        $waterInfo = $imageInfo = null;
        imagedestroy($image_im);
    }

    static protected function getImageInfo($img)
    {
        $imageInfo = getimagesize($img);
        if ($imageInfo !== false) {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
            $imageSize = filesize($img);
            $info = array(
                "width" => $imageInfo[0],
                "height" => $imageInfo[1],
                "type" => $imageType,
                "size" => $imageSize,
                "mime" => $imageInfo['mime']
                );
            return $info;
        } else {
            return false;
        }
    }

    static protected function output($im, $type = 'png', $filename = '')
    {
        header("Content-type: image/" . $type);
        $ImageFun = 'image' . $type;
        if (empty($filename)) {
            $ImageFun($im);
        } else {
            $ImageFun($im, $filename);
        }
        imagedestroy($im);
        exit;
    }
    
}

class Email 
{

    var $smtp_port;
    var $time_out;
    var $host_name;
    var $log_file;
    var $relay_host;
    var $debug;
    var $auth;
    var $user;
    var $pass;
    var $sock;

    public function config($relay_host = "", $smtp_port = 25,$auth = false,$user,$pass) {
        $this->debug = FALSE;
        $this->smtp_port = $smtp_port;
        $this->relay_host = $relay_host;
        $this->time_out = 30; //is used in fsockopen()
        $this->auth = $auth;//auth
        $this->user = $user;
        $this->pass = $pass;
        $this->host_name = "localhost"; //is used in HELO command
        $this->log_file ="";
        $this->sock = FALSE;
    }

    public function sendmail($to, $from, $subject = "", $body = "", $mailtype, $cc = "", $bcc = "", $additional_headers = "") {
        $mail_from = $this->get_address($this->strip_comment($from));
        $body = ereg_replace("(^|(\r\n))(\\.)", "\\1.\\3", $body);
        $header .= "MIME-Version:1.0\r\n";
        if($mailtype=="HTML"){
            $header .= "Content-Type:text/html;charset=utf-8 \r\n";
        }
        $header .= "To: ".$to."\r\n";
        if ($cc != "") {
            $header .= "Cc: ".$cc."\r\n";
        }
        $header .= "From: $from<".$from.">\r\n";
        $header .= "Subject: ".$subject."\r\n";
        $header .= $additional_headers;
        $header .= "Date: ".date("r")."\r\n";
        $header .= "X-Mailer:By Redhat (PHP/".phpversion().")\r\n";
        list($msec, $sec) = explode(" ", microtime());
        $header .= "Message-ID: <".date("YmdHis", $sec).".".($msec*1000000).".".$mail_from.">\r\n";
        $TO = explode(",", $this->strip_comment($to));
        
        if ($cc != "") {
            $TO = array_merge($TO, explode(",", $this->strip_comment($cc)));
        }
        
        if ($bcc != "") {
            $TO = array_merge($TO, explode(",", $this->strip_comment($bcc)));
        }
        
        $sent = TRUE;
        foreach ($TO as $rcpt_to) {
            $rcpt_to = $this->get_address($rcpt_to);
            if (!$this->smtp_sockopen($rcpt_to)) {
                $this->log_write("Error: Cannot send email to ".$rcpt_to."\n");
                $sent = FALSE;
                continue;
            }
            if ($this->smtp_send($this->host_name, $mail_from, $rcpt_to, $header, $body)) {
                $this->log_write("E-mail has been sent to <".$rcpt_to.">\n");
            } else {
                $this->log_write("Error: Cannot send email to <".$rcpt_to.">\n");
                $sent = FALSE;
            }
            fclose($this->sock);
            $this->log_write("Disconnected from remote host\n");
        }
        //echo "<br>";
        //echo $header;
        return $sent;
    }

    private function smtp_send($helo, $from, $to, $header, $body = "") {
        if (!$this->smtp_putcmd("HELO", $helo)) {
            return $this->smtp_error("sending HELO command");
        }
        #auth
        if($this->auth){
            if (!$this->smtp_putcmd("AUTH LOGIN", base64_encode($this->user))) {
                return $this->smtp_error("sending HELO command");
            }

            if (!$this->smtp_putcmd("", base64_encode($this->pass))) {
                return $this->smtp_error("sending HELO command");
            }
        }
        #
        if (!$this->smtp_putcmd("MAIL", "FROM:<".$from.">")) {
            return $this->smtp_error("sending MAIL FROM command");
        }
        
        if (!$this->smtp_putcmd("RCPT", "TO:<".$to.">")) {
            return $this->smtp_error("sending RCPT TO command");
        }
        
        if (!$this->smtp_putcmd("DATA")) {
            return $this->smtp_error("sending DATA command");
        }
        
        if (!$this->smtp_message($header, $body)) {
            return $this->smtp_error("sending message");
        }
        
        if (!$this->smtp_eom()) {
            return $this->smtp_error("sending <CR><LF>.<CR><LF> [EOM]");
        }
        
        if (!$this->smtp_putcmd("QUIT")) {
            return $this->smtp_error("sending QUIT command");
        }
        
        return TRUE;
    }

    private function smtp_sockopen($address)
    {
        if ($this->relay_host == "") {
            return $this->smtp_sockopen_mx($address);
        } else {
            return $this->smtp_sockopen_relay();
        }
    }

    private function smtp_sockopen_relay()
    {
        $this->log_write("Trying to ".$this->relay_host.":".$this->smtp_port."\n");
        $this->sock = @fsockopen($this->relay_host, $this->smtp_port, $errno, $errstr, $this->time_out);
        if (!($this->sock && $this->smtp_ok())) {
            $this->log_write("Error: Cannot connenct to relay host ".$this->relay_host."\n");
            $this->log_write("Error: ".$errstr." (".$errno.")\n");
            return FALSE;
        }
        $this->log_write("Connected to relay host ".$this->relay_host."\n");
        return TRUE;;
    }

    private function smtp_sockopen_mx($address) {
        $domain = ereg_replace("^.+@([^@]+)$", "\\1", $address);
        if (!@getmxrr($domain, $MXHOSTS)) {
            $this->log_write("Error: Cannot resolve MX \"".$domain."\"\n");
            return FALSE;
        }
        foreach ($MXHOSTS as $host) {
            $this->log_write("Trying to ".$host.":".$this->smtp_port."\n");
            $this->sock = @fsockopen($host, $this->smtp_port, $errno, $errstr, $this->time_out);
            if (!($this->sock && $this->smtp_ok())) {
                $this->log_write("Warning: Cannot connect to mx host ".$host."\n");
                $this->log_write("Error: ".$errstr." (".$errno.")\n");
                continue;
            }
            $this->log_write("Connected to mx host ".$host."\n");
            return TRUE;
        }
        $this->log_write("Error: Cannot connect to any mx hosts (".implode(", ", $MXHOSTS).")\n");
        return FALSE;
    }

    private function smtp_message($header, $body) {
        fputs($this->sock, $header."\r\n".$body);
        $this->smtp_debug("> ".str_replace("\r\n", "\n"."> ", $header."\n> ".$body."\n> "));
        return TRUE;
    }

    private function smtp_eom() {
        fputs($this->sock, "\r\n.\r\n");
        $this->smtp_debug(". [EOM]\n");
        
        return $this->smtp_ok();
    }

    private function smtp_ok() {
        $response = str_replace("\r\n", "", fgets($this->sock, 512));
        $this->smtp_debug($response."\n");
        
        if (!ereg("^[23]", $response)) {
            fputs($this->sock, "QUIT\r\n");
            fgets($this->sock, 512);
            $this->log_write("Error: Remote host returned \"".$response."\"\n");
            return FALSE;
        }
        return TRUE;
    }

    private function smtp_putcmd($cmd, $arg = "") {
        if ($arg != "") {
            if($cmd=="") $cmd = $arg;
            else $cmd = $cmd." ".$arg;
        }

        fputs($this->sock, $cmd."\r\n");
        $this->smtp_debug("> ".$cmd."\n");
        
        return $this->smtp_ok();
    }

    private function smtp_error($string) {
        $this->log_write("Error: Error occurred while ".$string.".\n");
        return FALSE;
    }

    private function log_write($message) {
        $this->smtp_debug($message);
        
        if ($this->log_file == "") {
            return TRUE;
        }

        $message = date("M d H:i:s ").get_current_user()."[".getmypid()."]: ".$message;
        if (!@file_exists($this->log_file) || !($fp = @fopen($this->log_file, "a"))) {
            $this->smtp_debug("Warning: Cannot open log file \"".$this->log_file."\"\n");
            return FALSE;
        }
        flock($fp, LOCK_EX);
        fputs($fp, $message);
        fclose($fp);
        
        return TRUE;
    }

    private function strip_comment($address) {
        $comment = "\\([^()]*\\)";
        while (ereg($comment, $address)) {
            $address = ereg_replace($comment, "", $address);
        }

        return $address;
    }

    private function get_address($address) {
        $address = ereg_replace("([ \t\r\n])+", "", $address);
        $address = ereg_replace("^.*<(.+)>.*$", "\\1", $address);
        
        return $address;
    }

    private function smtp_debug($message) {
        if ($this->debug) {
            echo $message."<br>";
        }
    }

    private function get_attach_type($image_tag) { //

        $filedata = array();
        
        $img_file_con=fopen($image_tag,"r");
        unset($image_data);
        while ($tem_buffer=AddSlashes(fread($img_file_con,filesize($image_tag))))
            $image_data.=$tem_buffer;
        fclose($img_file_con);
        
        $filedata['context'] = $image_data;
        $filedata['filename']= basename($image_tag);
        $extension=substr($image_tag,strrpos($image_tag,"."),strlen($image_tag)-strrpos($image_tag,"."));
        switch($extension){
            case ".gif":
            $filedata['type'] = "image/gif";
            break;
            case ".gz":
            $filedata['type'] = "application/x-gzip";
            break;
            case ".htm":
            $filedata['type'] = "text/html";
            break;
            case ".html":
            $filedata['type'] = "text/html";
            break;
            case ".jpg":
            $filedata['type'] = "image/jpeg";
            break;
            case ".tar":
            $filedata['type'] = "application/x-tar";
            break;
            case ".txt":
            $filedata['type'] = "text/plain";
            break;
            case ".zip":
            $filedata['type'] = "application/zip";
            break;
            default:
            $filedata['type'] = "application/octet-stream";
            break;
        }
        
        
        return $filedata;
    }

}

class Request
{

    /**
     * 判断是否为GET调用
     *
     * @access public
     * @return boolean
     */
    public static function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * 判断是否为POST调用
     *
     * @access public
     * @return boolean
     */
    public static function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * 判断是否为ajax调用
     *
     * @access public
     * @return boolean
     */
    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * 判断当前的网络协议是否为https安全请求
     *
     * @access public
     * @return boolean
     */
    public static function isSecure()
    {
        return isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1)
        || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
    }
     
    private static function _filter($string)
    {
        if(is_array($string)) {
            foreach ($string as $key => $value) {
                $string[$key] = self::_filter($value);
            }
        } else {
            $string = htmlspecialchars(trim($string));
        }
        return $string;
    }

    /**
     * 获取GET参数
     * 
     * @param string $name 数据名称
     * @param string $default 默认值     
     * @return mixed
     */
    public static function get($name = null, $default = null)
    {
        if ($name === null) {
            return self::_filter($_GET);
        } else {
            return isset($_GET[$name]) ? self::_filter($_GET[$name]) : $default;
        }
    }

    /**
     * 获取POST参数
     * 
     * @param string $name 数据名称
     * @param string $default 默认值     
     * @return mixed
     */
    public static function post($name = null, $default = null)
    {
        if ($name === null) {
            return self::_filter($_POST);
        } else {
            return isset($_POST[$name]) ? self::_filter($_POST[$name]) : $default;
        }
    }

    /**
     * 确认是否存在$_GET某参数值
     *
     * @access public
     * @param string $name 所要获取$_GET的参数名称
     * @return boolean
     */
    public static function hasGet($name = null)
    {
        if (isset($_GET[$name]) && trim($_GET[$name]) != '') {
            return true;
        }

        return false;
    }

}

class Upload
{

    protected $limit_size;
    protected $file_name;
    protected $limit_type;

    public function  __construct()
    {
        $this->limit_size = 83886080;
        return true;
    }

    protected function parse_init($file)
    {
        $this->file_name = $file;
        if ($this->file_name['size'] > $this->limit_size) {
            echo '您上传的文件:' . $this->file_name['name'] . ' 大小超出上传限制!';
            exit();
        }
        if ($this->limit_type) {
            if (in_array($this->get_file_ext(), array('asp','php','jsp'))) exit();
            if (!in_array($this->get_file_ext(), $this->limit_type)) {
                echo '您上传的:' . $this->file_name['name'] . ' 文件格式不正确!';
                exit();
            }
        }
        return true;
    }

    public function get_file_ext()
    {
        return strtolower(substr(strrchr($this->file_name['name'], '.'), 1));
    }

    public function set_limit_size($size)
    {
        if ($size) $this->limit_size = $size;
        return $this;
    }

    public function set_limit_type($type)
    {
        if (!$type || !is_array($type)) return false;
        $this->limit_type = $type;
        return $this;
    }

    public function upload($file_upload, $file_name)
    {
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        if (in_array($ext, array('asp','php','jsp'))) exit();
        if (!is_array($file_upload) || empty($file_name)) return false;
        $this->parse_init($file_upload);
        if (!@move_uploaded_file($this->file_name['tmp_name'], $file_name)) return '文件上传失败，请检查上传目录权限';
        return true;
    }

}

class Pager
{

    protected $_url = null;
    protected $_page = 1;
    protected $_total = 0;
    protected $_totalPages = 0;
    protected $_num = 10;
    protected $_perCircle = 10;
    protected $_ext = false;
    protected $_center = 3;
    protected $_isAjax = false;
    protected $_ajaxActionName = null;
    protected $_styleFile = null;
    protected $_hiddenStatus = false;
    public $firstPage = '第一页';
    public $prePage = '上一页';
    public $nextPage = '下一页';
    public $lastPage = '最末页';
    public $note = '<a  href="#">共{$totalNum}条</a>';

    protected function _getTotalPage()
    {
        return ceil($this->_total / $this->_num);
    }

    protected function _getPageNum()
    {
        return ($this->_page > $this->_totalPages) ? $this->_totalPages : $this->_page;
    }

    public function num($num = null)
    {
        if ($num) $this->_num = $num;
        return $this;
    }

    public function total($totalNum = null)
    {
        if(SYS_THEME_DIR == 'mobile'.DS) $this->_perCircle = 1;
        if ($totalNum) $this->_total = $totalNum;
        return $this;
    }

    public function hide($item = true)
    {
        if ($item === true) {
            $this->_hiddenStatus = true;
        }
        return $this;
    }

    public function url($url = null)
    {
        if ($url) {
            $this->_url = trim($url);
        }
        return $this;
    }

    public function page($page = null)
    {

        if ($page) {
            $this->_page = $page;
        }
        return $this;
    }

    public function ext($ext = true)
    {
        $this->_ext = ($ext) ? true : false;
        return $this;
    }

    public function center($num)
    {
        if ($num && is_int($num)) {
            $this->_center = $num;
        }
        return $this;
    }

    public function circle($num)
    {
        if ($num && is_int($num)) {
            $this->_perCircle = $num;
        }
        return $this;
    }

    public function ajax($action)
    {
        if ($action) {
            $this->_isAjax = true;
            $this->_ajaxActionName = $action;
        }
        return $this;
    }

    public function output()
    {
        $data = $this->_processData();
        if (!$data) return null;
        $html = '<div class="xiaocms-page">';
        if ($data['ext'] === true && $this->note) {
            $html .= str_replace(array('{$totalNum}', '{$totalPage}', '{$num}'), array($data['total'], $data['totalpage'], $data['num']), $this->note);
        }
        if (isset($data['prepage'])) {
            foreach ($data['prepage'] as $lines) {
                $content = ($data['ajax'] === true) ? "<a href='{$lines['url']}' onclick='{$data['ajaxaction']}('{$lines['url']}'); return false;'>{$lines['text']}</a>" : "<a href='{$lines['url']}' target='_self'>{$lines['text']}</a>";
                $html .= $content;
            }
        }
        foreach ($data['listpage'] as $lines) {
            if ($lines['current'] === true) {
                $html .= '<span >' . $lines['text'] . '</span >';
            } else {
                $content = ($data['ajax'] === true) ? "<a href='{$lines['url']}' onclick='{$data['ajaxaction']}('{$lines['url']}'); return false;'>{$lines['text']}</a>" : "<a href='{$lines['url']}' >{$lines['text']}</a>";
                $html .= $content;
            }
        }
        if (isset($data['nextpage'])) {
            foreach ($data['nextpage'] as $lines) {
                $content = ($data['ajax'] === true) ? "<a href='{$lines['url']}' onclick='{$data['ajaxaction']}('{$lines['url']}'); return false;'>{$lines['text']}</a>" : "<a href='{$lines['url']}' >{$lines['text']}</a>";
                $html .= $content;
            }
        }
        $html .= '</div>';
        return $html;
    }

    public function render()
    {
        return $this->_processData();
    }

    protected function _processData()
    {
        $this->_url = trim(str_replace(array("\n", "\r"), '', $this->_url));
        $this->_totalPages = $this->_getTotalPage();
        $this->_page = $this->_getPageNum();
        $data = array();
        if (!$this->_totalPages) {
            return $data;
        }
        if (($this->_hiddenStatus === true) && ($this->_total <= $this->_num)) {
            return $data;
        }
        $data['total'] = $this->_total;
        $data['num'] = $this->_num;
        $data['totalpage'] = $this->_totalPages;
        $data['page'] = $this->_page;
        $data['url'] = $this->_url;
        $data['ajax'] = $this->_isAjax;
        if ($this->_isAjax) {
            $data['ajaxAction'] = $this->_ajaxActionName;
        }
        $data['ext'] = $this->_ext;
        if ($this->_page != 1 && $this->_totalPages > 1) {
            $data['prepage'] = array(
                array('text' => $this->firstPage, 'url' => str_replace('[page]', 1, $this->_url)),
                array('text' => $this->prePage, 'url' => str_replace('[page]', ($this->_page - 1), $this->_url)),
                );
        }
        if ($this->_page != $this->_totalPages && $this->_totalPages > 1) {
            $data['nextpage'] = array(
                array('text' => $this->nextPage, 'url' => str_replace('[page]', ($this->_page + 1), $this->_url)),
                array('text' => $this->lastPage, 'url' => str_replace('[page]', $this->_totalPages, $this->_url)),
                );
        }
        if ($this->_totalPages > $this->_perCircle) {
            if ($this->_page + $this->_perCircle >= $this->_totalPages + $this->_center) {
                $list_start = $this->_totalPages - $this->_perCircle + 1;
                $list_end = $this->_totalPages;
            } else {
                $list_start = ($this->_page > $this->_center) ? $this->_page - $this->_center + 1 : 1;
                $list_end = ($this->_page > $this->_center) ? $this->_page + $this->_perCircle - $this->_center : $this->_perCircle;
            }
        } else {
            $list_start = 1;
            $list_end = $this->_totalPages;
        }
        for ($i = $list_start; $i <= $list_end; $i++) {
            if ($i == $this->_page) {
                $data['listpage'][] = array('text' => $i, 'current' => true);
            } else {
                $data['listpage'][] = array('text' => $i, 'current' => false, 'url' => str_replace('[page]', $i, $this->_url));
            }
        }
        return $data;
    }

}

class Pinyin
{

    protected $_lib;

    protected function _num2str($num)
    {
        if (!$this->_lib) $this->_parseLib();
        if ($num > 0 && $num < 160) {
            return chr($num);
        } elseif ($num < -20319 || $num > -10247) {
            return '';
        } else {
            $total = sizeof($this->_lib) - 1;
            for ($i = $total; $i >= 0; $i--) {
                if ($this->_lib[$i][1] <= $num) {
                    break;
                }
            }
            return $this->_lib[$i][0];
        }
    }

    protected function _parseLib()
    {
        return $this->_lib = array(
            array("a", -20319),
            array("ai", -20317),
            array("an", -20304),
            array("ang", -20295),
            array("ao", -20292),
            array("ba", -20283),
            array("bai", -20265),
            array("ban", -20257),
            array("bang", -20242),
            array("bao", -20230),
            array("bei", -20051),
            array("ben", -20036),
            array("beng", -20032),
            array("bi", -20026),
            array("bian", -20002),
            array("biao", -19990),
            array("bie", -19986),
            array("bin", -19982),
            array("bing", -19976),
            array("bo", -19805),
            array("bu", -19784),
            array("ca", -19775),
            array("cai", -19774),
            array("can", -19763),
            array("cang", -19756),
            array("cao", -19751),
            array("ce", -19746),
            array("ceng", -19741),
            array("cha", -19739),
            array("chai", -19728),
            array("chan", -19725),
            array("chang", -19715),
            array("chao", -19540),
            array("che", -19531),
            array("chen", -19525),
            array("cheng", -19515),
            array("chi", -19500),
            array("chong", -19484),
            array("chou", -19479),
            array("chu", -19467),
            array("chuai", -19289),
            array("chuan", -19288),
            array("chuang", -19281),
            array("chui", -19275),
            array("chun", -19270),
            array("chuo", -19263),
            array("ci", -19261),
            array("cong", -19249),
            array("cou", -19243),
            array("cu", -19242),
            array("cuan", -19238),
            array("cui", -19235),
            array("cun", -19227),
            array("cuo", -19224),
            array("da", -19218),
            array("dai", -19212),
            array("dan", -19038),
            array("dang", -19023),
            array("dao", -19018),
            array("de", -19006),
            array("deng", -19003),
            array("di", -18996),
            array("dian", -18977),
            array("diao", -18961),
            array("die", -18952),
            array("ding", -18783),
            array("diu", -18774),
            array("dong", -18773),
            array("dou", -18763),
            array("du", -18756),
            array("duan", -18741),
            array("dui", -18735),
            array("dun", -18731),
            array("duo", -18722),
            array("e", -18710),
            array("en", -18697),
            array("er", -18696),
            array("fa", -18526),
            array("fan", -18518),
            array("fang", -18501),
            array("fei", -18490),
            array("fen", -18478),
            array("feng", -18463),
            array("fo", -18448),
            array("fou", -18447),
            array("fu", -18446),
            array("ga", -18239),
            array("gai", -18237),
            array("gan", -18231),
            array("gang", -18220),
            array("gao", -18211),
            array("ge", -18201),
            array("gei", -18184),
            array("gen", -18183),
            array("geng", -18181),
            array("gong", -18012),
            array("gou", -17997),
            array("gu", -17988),
            array("gua", -17970),
            array("guai", -17964),
            array("guan", -17961),
            array("guang", -17950),
            array("gui", -17947),
            array("gun", -17931),
            array("guo", -17928),
            array("ha", -17922),
            array("hai", -17759),
            array("han", -17752),
            array("hang", -17733),
            array("hao", -17730),
            array("he", -17721),
            array("hei", -17703),
            array("hen", -17701),
            array("heng", -17697),
            array("hong", -17692),
            array("hou", -17683),
            array("hu", -17676),
            array("hua", -17496),
            array("huai", -17487),
            array("huan", -17482),
            array("huang", -17468),
            array("hui", -17454),
            array("hun", -17433),
            array("huo", -17427),
            array("ji", -17417),
            array("jia", -17202),
            array("jian", -17185),
            array("jiang", -16983),
            array("jiao", -16970),
            array("jie", -16942),
            array("jin", -16915),
            array("jing", -16733),
            array("jiong", -16708),
            array("jiu", -16706),
            array("ju", -16689),
            array("juan", -16664),
            array("jue", -16657),
            array("jun", -16647),
            array("ka", -16474),
            array("kai", -16470),
            array("kan", -16465),
            array("kang", -16459),
            array("kao", -16452),
            array("ke", -16448),
            array("ken", -16433),
            array("keng", -16429),
            array("kong", -16427),
            array("kou", -16423),
            array("ku", -16419),
            array("kua", -16412),
            array("kuai", -16407),
            array("kuan", -16403),
            array("kuang", -16401),
            array("kui", -16393),
            array("kun", -16220),
            array("kuo", -16216),
            array("la", -16212),
            array("lai", -16205),
            array("lan", -16202),
            array("lang", -16187),
            array("lao", -16180),
            array("le", -16171),
            array("lei", -16169),
            array("leng", -16158),
            array("li", -16155),
            array("lia", -15959),
            array("lian", -15958),
            array("liang", -15944),
            array("liao", -15933),
            array("lie", -15920),
            array("lin", -15915),
            array("ling", -15903),
            array("liu", -15889),
            array("long", -15878),
            array("lou", -15707),
            array("lu", -15701),
            array("lv", -15681),
            array("luan", -15667),
            array("lue", -15661),
            array("lun", -15659),
            array("luo", -15652),
            array("ma", -15640),
            array("mai", -15631),
            array("man", -15625),
            array("mang", -15454),
            array("mao", -15448),
            array("me", -15436),
            array("mei", -15435),
            array("men", -15419),
            array("meng", -15416),
            array("mi", -15408),
            array("mian", -15394),
            array("miao", -15385),
            array("mie", -15377),
            array("min", -15375),
            array("ming", -15369),
            array("miu", -15363),
            array("mo", -15362),
            array("mou", -15183),
            array("mu", -15180),
            array("na", -15165),
            array("nai", -15158),
            array("nan", -15153),
            array("nang", -15150),
            array("nao", -15149),
            array("ne", -15144),
            array("nei", -15143),
            array("nen", -15141),
            array("neng", -15140),
            array("ni", -15139),
            array("nian", -15128),
            array("niang", -15121),
            array("niao", -15119),
            array("nie", -15117),
            array("nin", -15110),
            array("ning", -15109),
            array("niu", -14941),
            array("nong", -14937),
            array("nu", -14933),
            array("nv", -14930),
            array("nuan", -14929),
            array("nue", -14928),
            array("nuo", -14926),
            array("o", -14922),
            array("ou", -14921),
            array("pa", -14914),
            array("pai", -14908),
            array("pan", -14902),
            array("pang", -14894),
            array("pao", -14889),
            array("pei", -14882),
            array("pen", -14873),
            array("peng", -14871),
            array("pi", -14857),
            array("pian", -14678),
            array("piao", -14674),
            array("pie", -14670),
            array("pin", -14668),
            array("ping", -14663),
            array("po", -14654),
            array("pu", -14645),
            array("qi", -14630),
            array("qia", -14594),
            array("qian", -14429),
            array("qiang", -14407),
            array("qiao", -14399),
            array("qie", -14384),
            array("qin", -14379),
            array("qing", -14368),
            array("qiong", -14355),
            array("qiu", -14353),
            array("qu", -14345),
            array("quan", -14170),
            array("que", -14159),
            array("qun", -14151),
            array("ran", -14149),
            array("rang", -14145),
            array("rao", -14140),
            array("re", -14137),
            array("ren", -14135),
            array("reng", -14125),
            array("ri", -14123),
            array("rong", -14122),
            array("rou", -14112),
            array("ru", -14109),
            array("ruan", -14099),
            array("rui", -14097),
            array("run", -14094),
            array("ruo", -14092),
            array("sa", -14090),
            array("sai", -14087),
            array("san", -14083),
            array("sang", -13917),
            array("sao", -13914),
            array("se", -13910),
            array("sen", -13907),
            array("seng", -13906),
            array("sha", -13905),
            array("shai", -13896),
            array("shan", -13894),
            array("shang", -13878),
            array("shao", -13870),
            array("she", -13859),
            array("shen", -13847),
            array("sheng", -13831),
            array("shi", -13658),
            array("shou", -13611),
            array("shu", -13601),
            array("shua", -13406),
            array("shuai", -13404),
            array("shuan", -13400),
            array("shuang", -13398),
            array("shui", -13395),
            array("shun", -13391),
            array("shuo", -13387),
            array("si", -13383),
            array("song", -13367),
            array("sou", -13359),
            array("su", -13356),
            array("suan", -13343),
            array("sui", -13340),
            array("sun", -13329),
            array("suo", -13326),
            array("ta", -13318),
            array("tai", -13147),
            array("tan", -13138),
            array("tang", -13120),
            array("tao", -13107),
            array("te", -13096),
            array("teng", -13095),
            array("ti", -13091),
            array("tian", -13076),
            array("tiao", -13068),
            array("tie", -13063),
            array("ting", -13060),
            array("tong", -12888),
            array("tou", -12875),
            array("tu", -12871),
            array("tuan", -12860),
            array("tui", -12858),
            array("tun", -12852),
            array("tuo", -12849),
            array("wa", -12838),
            array("wai", -12831),
            array("wan", -12829),
            array("wang", -12812),
            array("wei", -12802),
            array("wen", -12607),
            array("weng", -12597),
            array("wo", -12594),
            array("wu", -12585),
            array("xi", -12556),
            array("xia", -12359),
            array("xian", -12346),
            array("xiang", -12320),
            array("xiao", -12300),
            array("xie", -12120),
            array("xin", -12099),
            array("xing", -12089),
            array("xiong", -12074),
            array("xiu", -12067),
            array("xu", -12058),
            array("xuan", -12039),
            array("xue", -11867),
            array("xun", -11861),
            array("ya", -11847),
            array("yan", -11831),
            array("yang", -11798),
            array("yao", -11781),
            array("ye", -11604),
            array("yi", -11589),
            array("yin", -11536),
            array("ying", -11358),
            array("yo", -11340),
            array("yo", -11340),
            array("yong", -11339),
            array("you", -11324),
            array("yu", -11303),
            array("yuan", -11097),
            array("yue", -11077),
            array("yun", -11067),
            array("za", -11055),
            array("zai", -11052),
            array("zan", -11045),
            array("zang", -11041),
            array("zao", -11038),
            array("ze", -11024),
            array("zei", -11020),
            array("zen", -11019),
            array("zeng", -11018),
            array("zha", -11014),
            array("zhai", -10838),
            array("zhan", -10832),
            array("zhang", -10815),
            array("zhao", -10800),
            array("zhe", -10790),
            array("zhen", -10780),
            array("zheng", -10764),
            array("zhi", -10587),
            array("zhong", -10544),
            array("zhou", -10533),
            array("zhu", -10519),
            array("zhua", -10331),
            array("zhuai", -10329),
            array("zhuan", -10328),
            array("zhuang", -10322),
            array("zhui", -10315),
            array("zhun", -10309),
            array("zhuo", -10307),
            array("zi", -10296),
            array("zong", -10281),
            array("zou", -10274),
            array("zu", -10270),
            array("zuan", -10262),
            array("zui", -10260),
            array("zun", -10256),
            array("zuo", -10254),
        );
    }

    public function output($string, $utf8 = true)
    {
        if (!$string) return false;
        $string = ($utf8 == true) ? iconv('utf-8', 'gbk', $string) : $string;
        $num = strlen($string);
        $pinyin = '';
        for ($i = 0; $i < $num; $i++) {
            $temp = ord(substr($string, $i, 1));
            if ($temp > 160) {
                $temp2 = ord(substr($string, ++$i, 1));
                $temp = $temp * 256 + $temp2 - 65536;
            }
            $pinyin .= $this->_num2str($temp);
        }
        return ($utf8 == true) ? iconv('gbk', 'utf-8', $pinyin) : $pinyin;
    }

    public function __destruct()
    {
        if (isset($this->_lib)) {
            unset($this->_lib);
        }
    }
}
class Uploader
{
    private $fileField; //文件域名
    private $file; //文件上传对象
    private $base64; //文件上传对象
    private $config; //配置信息
    private $oriName; //原始文件名
    private $fileName; //新文件名
    private $fullName; //完整文件名,即从当前配置目录开始的URL
    private $filePath; //完整文件名,即从当前配置目录开始的URL
    private $fileSize; //文件大小
    private $fileType; //文件类型
    private $stateInfo; //上传状态信息,
    private $stateMap = array( //上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS", //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "ERROR_TMP_FILE" => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED" => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED" => "文件类型不允许",
        "ERROR_CREATE_DIR" => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE" => "目录没有写权限",
        "ERROR_FILE_MOVE" => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND" => "找不到上传文件",
        "ERROR_WRITE_CONTENT" => "写入文件内容错误",
        "ERROR_UNKNOWN" => "未知错误",
        "ERROR_DEAD_LINK" => "链接不可用",
        "ERROR_HTTP_LINK" => "链接不是http链接",
        "ERROR_HTTP_CONTENTTYPE" => "链接contentType不正确",
        "INVALID_URL" => "非法 URL",
        "INVALID_IP" => "非法 IP"
    );

    /**
     * 构造函数
     * @param string $fileField 表单名称
     * @param array $config 配置项
     * @param bool $base64 是否解析base64编码，可省略。若开启，则$fileField代表的是base64编码的字符串表单名
     */
    public function __construct($fileField, $config, $type = "upload")
    {
        $this->fileField = $fileField;
        $this->config = $config;
        $this->type = $type;
        if ($type == "remote") {
            $this->saveRemote();
        } else if($type == "base64") {
            $this->upBase64();
        } else {
            $this->upFile();
        }
    }

    /**
     * 上传文件的主处理方法
     * @return mixed
     */
    private function upFile()
    {
        $file = $this->file = $_FILES[$this->fileField];
        if (!$file) {
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_NOT_FOUND");
            return;
        }
        if ($this->file['error']) {
            $this->stateInfo = $this->getStateInfo($file['error']);
            return;
        } else if (!file_exists($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMP_FILE_NOT_FOUND");
            return;
        } else if (!is_uploaded_file($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMPFILE");
            return;
        }

        $this->oriName = $file['name'];
        $this->fileSize = $file['size'];
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //检查是否不允许的文件格式
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        //移动文件
        if (!(move_uploaded_file($file["tmp_name"], $this->filePath) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_MOVE");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
        }
    }

    /**
     * 处理base64编码的图片上传
     * @return mixed
     */
    private function upBase64()
    {
        $base64Data = $_POST[$this->fileField];
        $img = base64_decode($base64Data);

        $this->oriName = $this->config['oriName'];
        $this->fileSize = strlen($img);
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        //移动文件
        if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
        }

    }

    /**
     * 拉取远程图片
     * @return mixed
     */
    private function saveRemote()
    {
        $imgUrl = htmlspecialchars($this->fileField);
        $imgUrl = str_replace("&amp;", "&", $imgUrl);

        //http开头验证
        if (strpos($imgUrl, "http") !== 0) {
            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_LINK");
            return;
        }

        preg_match('/(^https*:\/\/[^:\/]+)/', $imgUrl, $matches);
        $host_with_protocol = count($matches) > 1 ? $matches[1] : '';

        // 判断是否是合法 url
        if (!filter_var($host_with_protocol, FILTER_VALIDATE_URL)) {
            $this->stateInfo = $this->getStateInfo("INVALID_URL");
            return;
        }

        preg_match('/^https*:\/\/(.+)/', $host_with_protocol, $matches);
        $host_without_protocol = count($matches) > 1 ? $matches[1] : '';

        // 此时提取出来的可能是 ip 也有可能是域名，先获取 ip
        $ip = gethostbyname($host_without_protocol);
        // 判断是否是私有 ip
        if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            $this->stateInfo = $this->getStateInfo("INVALID_IP");
            return;
        }

        //获取请求头并检测死链
        $heads = get_headers($imgUrl, 1);
        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
            $this->stateInfo = $this->getStateInfo("ERROR_DEAD_LINK");
            return;
        }
        //格式验证(扩展名验证和Content-Type验证)
        $fileType = strtolower(strrchr($imgUrl, '.'));
        if (!in_array($fileType, $this->config['allowFiles']) || !isset($heads['Content-Type']) || !stristr($heads['Content-Type'], "image")) {
            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_CONTENTTYPE");
            return;
        }

        //打开输出缓冲区并获取远程图片
        ob_start();
        $context = stream_context_create(
            array('http' => array(
                'follow_location' => false // don't follow redirects
            ))
        );
        readfile($imgUrl, false, $context);
        $img = ob_get_contents();
        ob_end_clean();
        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);

        $this->oriName = $m ? $m[1]:"";
        $this->fileSize = strlen($img);
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        //移动文件
        if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
        }

    }

    /**
     * 上传错误检查
     * @param $errCode
     * @return string
     */
    private function getStateInfo($errCode)
    {
        return !$this->stateMap[$errCode] ? $this->stateMap["ERROR_UNKNOWN"] : $this->stateMap[$errCode];
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt()
    {
        return strtolower(strrchr($this->oriName, '.'));
    }

    /**
     * 重命名文件
     * @return string
     */
    private function getFullName()
    {
        //替换日期事件
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
        $format = $this->config["pathFormat"];
        $format = str_replace("{yyyy}", $d[0], $format);
        $format = str_replace("{yy}", $d[1], $format);
        $format = str_replace("{mm}", $d[2], $format);
        $format = str_replace("{dd}", $d[3], $format);
        $format = str_replace("{hh}", $d[4], $format);
        $format = str_replace("{ii}", $d[5], $format);
        $format = str_replace("{ss}", $d[6], $format);
        $format = str_replace("{time}", $t, $format);

        //过滤文件名的非法自负,并替换文件名
        $oriName = substr($this->oriName, 0, strrpos($this->oriName, '.'));
        $oriName = preg_replace("/[\|\?\"\<\>\/\*\\\\]+/", '', $oriName);
        $format = str_replace("{filename}", $oriName, $format);

        //替换随机字符串
        $randNum = rand(1, 10000000000) . rand(1, 10000000000);
        if (preg_match("/\{rand\:([\d]*)\}/i", $format, $matches)) {
            $format = preg_replace("/\{rand\:[\d]*\}/i", substr($randNum, 0, $matches[1]), $format);
        }

        $ext = $this->getFileExt();
        return $format . $ext;
    }

    /**
     * 获取文件名
     * @return string
     */
    private function getFileName () {
        return substr($this->filePath, strrpos($this->filePath, '/') + 1);
    }

    /**
     * 获取文件完整路径
     * @return string
     */
    private function getFilePath()
    {
        $fullname = $this->fullName;
        if (substr($fullname, 0, 1) != '/') {
            $fullname = '/' . $fullname;
        }

        return XIAOCMS_PATH . $fullname;
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private function checkType()
    {
        return in_array($this->getFileExt(), $this->config["allowFiles"]);
    }

    /**
     * 文件大小检测
     * @return bool
     */
    private function  checkSize()
    {
        return $this->fileSize <= ($this->config["maxSize"]);
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    public function getFileInfo()
    {
        return array(
            "state" => $this->stateInfo,
            "url" => SITE_PATH.$this->fullName,
            "title" => $this->fileName,
            "original" => $this->oriName,
            "type" => $this->fileType,
            "size" => $this->fileSize
        );
    }

}