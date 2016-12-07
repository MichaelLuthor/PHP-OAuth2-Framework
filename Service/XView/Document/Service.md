## XView 服务使用文档
更新日期：2015年12月24日

**简介**

该服务用于管理或渲染视图文件.

**基本使用方法**

    /* 获取服务实例。 */
    $viewService = X::system()->getServiceManager()->get(XViewService::getServiceName());
    
    /* 创建HTML视图。 */
    $view = $viewService->createHtml('ViewName');
    
    /* 设置页面编码。 */
    $view->getMetaManager()->setCharset('UTF-8');
    
    /* 格式化输出内容。 */
    $view->format(HtmlFormatter::FORMAT_PRETTY);
    
    /* 获取碎片视图管理器。 */
    $particleManager = $view->getParticleViewManager();
    
    /* 加载碎片视图。 */
    $particle = $particleManager->load('BasicAssetsLoader', '/path/of/particle/view');
    
    /* 像碎片中赋值。 */
    $particle->getDataManager()->set('name', 'value');
    
    /* 设置布局文件。 */
    $view->setLayout('/var/tmp/layout.php');
    
    /* 设置视图标题。 */
    $view->title = $title.' - '.$systemName;
    
    /* 显示视图。 */
    $view->display();
    
**其他方法**

- createHtml($name) 创建HTML视图。
- has( $name ) 检查视图是否存在。
- get($name) 获取视图。
- getList() 获取视图列表。
- activeView($name) 激活指定名字，服务运行过程中，只能存在一个激活的视图。
- display() 显示被激活的视图。