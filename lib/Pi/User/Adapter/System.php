<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Pi\User\Adapter;

use Pi;
//use Pi\User\Model\System as UserModel;

/**
 * Pi Engine built-in user service provided by system module
 *
 * @author Taiwen Jiang <taiwenjiang@tsinghua.org.cn>
 */
class System extends AbstractAdapter
{
    /** @var string Route for user URLs */
    //protected $route = 'sysuser';

    /**#@+
     * Meta operations
     */
    /**
     * {@inheritDoc}
     */
    public function getMeta($type = '', $action = '')
    {
        return Pi::api('user', 'system')->getMeta($type, $action);
    }
    /**#@-*/

    /**#@+
     * User operations
     */
    /**
     * {@inheritDoc}
     */
    public function getUser($uid = null, $field = 'id')
    {
        if ($this->model
            && (null === $uid || $this->model->get($field) == $uid)
        ) {
            $model = $this->model;
        } else {
            $model = $this->getUserModel($uid, $field);
        }

        return $model;
    }

    /**
     * {@inheritDoc}
     */
    public function getUids(
        $condition  = array(),
        $limit      = 0,
        $offset     = 0,
        $order      = ''
    ) {
        $result = Pi::api('user', 'system')->getUids(
            $condition,
            $limit,
            $offset,
            $order
        );

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getList(
        array $condition  = array(),
        $limit      = 0,
        $offset     = 0,
        $order      = '',
        array $field  = array()
    ) {
        $result = Pi::api('user', 'system')->getList(
            $condition,
            $limit,
            $offset,
            $order,
            $field
        );

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getCount($condition = array())
    {
        $result = Pi::api('user', 'system')->getCount($condition);

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function addUser($data, $setRole = true)
    {
        return Pi::api('user', 'system')->addUser($data, $setRole);
    }

    /**
     * {@inheritDoc}
     */
    public function updateUser($uid, $data)
    {
        return Pi::api('user', 'system')->updateUser($uid, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteUser($uid)
    {
        if ($this->isRoot($uid)) {
            return false;
        }
        return Pi::api('user', 'system')->deleteUser($uid);
    }

    /**
     * {@inheritDoc}
     */
    public function activateUser($uid)
    {
        return Pi::api('user', 'system')->activateUser($uid);
    }

    /**
     * {@inheritDoc}
     */
    public function enableUser($uid)
    {
        return Pi::api('user', 'system')->enableUser($uid);
    }

    /**
     * {@inheritDoc}
     */
    public function disableUser($uid)
    {
        if ($this->isRoot($uid)) {
            return false;
        }
        return Pi::api('user', 'system')->disableUser($uid);
    }
    /**#@-*/

    /**#@+
     * User account/Profile fields operations
     */
    /**
     * {@inheritDoc}
     */
    public function get(
        $uid,
        $field = array(),
        $filter = false,
        $activeOnly = false
    ) {
        return Pi::api('user', 'system')->get(
            $uid,
            $field,
            $filter,
            $activeOnly
        );
    }

    /**
     * {@inheritDoc}
     */
    public function mget(
        array $uids,
        $field = array(),
        $filter = false,
        $activeOnly = false
    ) {
        return Pi::api('user', 'system')->mget(
            $uids,
            $field,
            $filter,
            $activeOnly
        );
    }

    /**
     * {@inheritDoc}
     */
    public function set($uid, $field, $value)
    {
        return Pi::api('user', 'system')->set($uid, $field, $value);
    }
    /**#@-*/

    /**
     * {@inheritDoc}
     */
    public function setRole($uid, $role)
    {
        return Pi::api('user', 'system')->setRole($uid, $role);
    }

    /**
     * {@inheritDoc}
     */
    public function revokeRole($uid, $role)
    {
        return Pi::api('user', 'user')->revokeRole($uid, $role);
    }

    /**
     * {@inheritDoc}
     */
    public function getRole($uid, $section = '')
    {
        return Pi::api('user', 'system')->getRole($uid, $section);
    }

    /**#@+
     * Utility APIs
     */
    /**
     * {@inheritDoc}
     */
    public function getRoute()
    {
        return Pi::api('user', 'system')->getRoute();
    }

    /**
     * {@inheritDoc}
     *
     * @see http://httpd.apache.org/docs/2.2/mod/core.html#allowencodedslashes
     */
    public function getUrl($type, $var = null)
    {
        return Pi::api('user', 'system')->getUrl($type, $var);

        /*
        $route      = $this->getRoute();
        $redirect   = '';
        switch ($type) {
            case 'profile':
                $params = array();
                if (is_numeric($var)) {
                    $params['id'] = (int) $var;
                } elseif (is_string($var)) {
                    $params['name'] = $var;
                } else {
                    $params = (array) $var;
                }
                if (!isset($params['controller'])) {
                    $params['controller'] = 'profile';
                }
                if (isset($params['route'])) {
                    $route = $params['route'];
                    unset($params['route']);
                }
                $url = Pi::service('url')->assemble($route, $params);
                break;

            case 'login':
                if (is_string($var)) {
                    $params = array(
                        'redirect' => $var,
                    );
                } else {
                    $params = (array) $var;
                }
                if (isset($params['redirect'])) {
                    $redirect = $params['redirect'];
                    unset($params['redirect']);
                } else {
                    $redirect = Pi::service('url')->getRequestUri();
                }
                if (!isset($params['module'])) {
                    $params['module'] = 'system';
                }
                if (!isset($params['controller'])) {
                    $params['controller'] = 'login';
                }
                if (isset($params['route'])) {
                    $route = $params['route'];
                    unset($params['route']);
                }
                if (isset($params['section'])) {
                    $section = $params['section'];
                    unset($params['section']);
                } else {
                    $section = Pi::engine()->application()->getSection();
                }
                if ('admin' == $section) {
                    $route = 'admin';
                }
                $url = Pi::service('url')->assemble($route, $params);
                break;

            case 'logout':
                if (is_string($var)) {
                    $params = array(
                        'redirect' => $var,
                    );
                } else {
                    $params = (array) $var;
                }
                if (isset($params['redirect'])) {
                    $redirect = $params['redirect'];
                    unset($params['redirect']);
                }
                if (!isset($params['module'])) {
                    $params['module'] = 'system';
                }
                if (!isset($params['controller'])) {
                    $params['controller'] = 'login';
                }
                if (!isset($params['action'])) {
                    $params['action'] = 'logout';
                }
                if (isset($params['route'])) {
                    $route = $params['route'];
                    unset($params['route']);
                }
                if (isset($params['section'])) {
                    $section = $params['section'];
                    unset($params['section']);
                } else {
                    $section = Pi::engine()->application()->getSection();
                }
                if ('admin' == $section) {
                    $route = 'admin';
                }
                $url = Pi::service('url')->assemble($route, $params);
                break;

            case 'register':
                $params = (array) $var;
                if (!isset($params['controller'])) {
                    $params['controller'] = 'register';
                }
                if (isset($params['route'])) {
                    $route = $params['route'];
                    unset($params['route']);
                }
                $url = Pi::service('url')->assemble($route, $params);
                break;

            case 'password':
                $params = (array) $var;
                if (!isset($params['controller'])) {
                    $params['controller'] = 'password';
                }
                if (isset($params['route'])) {
                    $route = $params['route'];
                    unset($params['route']);
                }
                $url = Pi::service('url')->assemble($route, $params);
                break;

            default:
            case 'home':
                $params = array();
                if (is_numeric($var)) {
                    $params['id'] = (int) $var;
                } elseif (is_string($var)) {
                    $params['name'] = $var;
                } else {
                    $params = (array) $var;
                }
                if (!isset($params['controller'])) {
                    $params['controller'] = 'home';
                }
                if (isset($params['route'])) {
                    $route = $params['route'];
                    unset($params['route']);
                }
                $url = Pi::service('url')->assemble($route, $params);
                break;
        }

        // Append redirect with query
        // @see http://httpd.apache.org/docs/2.2/mod/core.html#allowencodedslashes
        if ($redirect) {
            if (false == strpos($url, '?')) {
                $url .= '?redirect=' . rawurlencode($redirect);
            } else {
                $url .= '&redirect=' . rawurlencode($redirect);
            }
        }

        return $url;
        */
    }

    /**
     * {@inheritDoc}
     */
    public function killUser($uid)
    {
        $result = Pi::service('session')->killUser($uid);

        return $result;
    }

    /**#@-*/

    /**
     * {@inheritDoc}
     */
    public function getUserModel($uid, $field = 'id')
    {
        $model = Pi::api('user', 'system')->getUser($uid, $field);

        return $model;
    }
}
