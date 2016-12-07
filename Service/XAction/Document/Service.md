## XAction 服务使用文档
更新日期：2015年12月23日

**简介**

该服务用于将动作分组并根据参数执行指定动作。

**基本使用方法**

    $parameters = array('action'=>'text/action/name');
    /* 获取服务实例。 */
    $actionService = X::system()->getServiceManager()->get(XActionService::getServiceName());
    
    /* 所有动作的命名空间都是由指定命名空间的基础上增加‘Action’来获得， 所以这里生成基础命名空间。 */
    $namespace = get_class($this);
    $namespace = substr($namespace, 0, strrpos($namespace, '\\'));
    
    /* 所有注册的动作被分组存在于服务中， 所以这里需要指定组名。 */
    $group = $this->getName();
    $actionService->addGroup($group, $namespace);
    
    /* 将运行参数赋值给服务，其中，键值为‘action’的元素用于指定动作的名称。 */
    $actionService->getParameterManager()->merge($parameters);
    
    /* 指定当该组没有指定动作名称时所执行的默认动作名称。 */
    $actionService->setGroupDefaultAction($group, $this->getDefaultActionName());
    
    /* 执行指定组。  该方法调用后会执行该组中的指定动作， 如果没有指定， 则执行默认动作。*/
    $actionService->runGroup($group);
    
**其他方法**

- register($group, $action, $handler) 注册动作处理器到指定分组。
- setGroupDefaultAction($groupName, $action) 设置分组默认执行动作。
- runAction($group, $action) 运动指定分组下的动作。
- getRunningAction() 获取正在运动的动作实例。
- runGroup($name) 运行指定分组。
- hasGroup($name) 检查分组是否存在。
- addGroup($name, $namespace) 注册分组到该服务。
- getParameterManager() 获取服务参数管理器。

**动作处理器说明**

所有动作处理器需要继承以下三个动作基类中的一个， 否则该动作将被视为一个无效的动作：

- X\Service\XAction\Core\Util\Action 所有动作的基类， 当无动作基类能够继承时， 使用该类。
- X\Service\XAction\Core\Handler\WebAction 用于处理web请求的基类。
- X\Service\XAction\Core\Handler\CommandAction 用于处理命令行的基类。

每个动作处理器需要实现“runAction”方法， 并且为public， 该方法的参数数量由用户自定义， 服务将自动将相应参数应用到该方法并进行调用。
例如:

    public function runAction( $name, $age ) {
        echo $name,':',$age;
    }

如果服务的参数为`array('name'=>'michael', 'age'=>10)`, 则该Action的输出为`michael:10`;