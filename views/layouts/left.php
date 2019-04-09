<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Сергей Desh</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?php try {
            echo dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                    'items' => [
                        ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                        ['label' => 'Задачник', 'icon' => 'calendar', 'url' => ['/task']],
                        [
                            'label' => 'Справочник',
                            'icon' => 'file-code',
                            'url' => '#',
                            'items' => [
                                ['label' => 'Проекты', 'icon' => 'file-code', 'url' => ['/project']],
                                ['label' => 'Заказчики', 'icon' => 'user-o', 'url' => ['/boss']],
                                ['label' => 'Месенджеры', 'icon' => 'user-o', 'url' => ['/messenger']],
                            ]
                        ],
                        ['label' => 'GoogleDrive', 'icon' => 'user-o', 'url' => ['/site/google-drive']],
                        ['label' => 'Drive модуль', 'icon' => 'user-o', 'url' => ['/drive']],
                        ['label' => 'BackUp', 'icon' => 'user-o', 'url' => ['/site/backup']],
                        ['label' => 'Gii', 'icon' => 'user-o', 'url' => ['/gii']],
                        ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug']],
                        ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest, 'icon' => 'id-card'],
                        [
                            'label' => 'Some tools',
                            'icon' => 'share',
                            'url' => '#',
                            'items' => [
                                ['label' => 'Gii', 'icon' => 'file-code', 'url' => ['/gii'],],
                                ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'],],
                                [
                                    'label' => 'Level One',
                                    'icon' => 'circle',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'Level Two', 'icon' => 'circle', 'url' => '#',],
                                        [
                                            'label' => 'Level Two',
                                            'icon' => 'circle',
                                            'url' => '#',
                                            'items' => [
                                                ['label' => 'Level Three', 'icon' => 'circle', 'url' => '#',],
                                                ['label' => 'Level Three', 'icon' => 'circle', 'url' => '#',],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            );
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            Yii::error($e->getTraceAsString(), __METHOD__);
        } ?>

    </section>

</aside>
