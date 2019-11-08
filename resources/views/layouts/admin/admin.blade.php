@extends('layouts.admin.base')

@section('head')
    <title>{{$siteName ?? '南海青商会'}}</title>
@endsection

@section('body')
<div class="layui-layout layui-layout-admin">
        <div class="layui-header">
          <div class="layui-logo"><a href="/admin/index/" class="link-logo">{{$siteName ?? '南海青商会'}}</a></div>
          <!-- 头部区域（可配合layui已有的水平导航） -->
          <ul class="layui-nav layui-layout-left" lay-filter="dashboard">
            <li class="layui-nav-item" >
                <a href="/admin/config/index/#tMenu_config" id="tMenu_config" class="menu_config">设置</a>
                {{-- <dl class="layui-nav-child">
                    <dd><a href="">基本配置</a></dd>
                </dl> --}}
            </li>
          </ul>
          <ul class="layui-nav layui-layout-right">
            {{-- <li class="layui-nav-item">
              <a href="javascript:;">
                <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
                贤心
              </a>
            </li> --}}
            <li class="layui-nav-item"><a href="" id="logout_btn">退出</a></li>
          </ul>
        </div>

        <div class="layui-side layui-bg-black">
          <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree" lay-shrink="all" lay-filter="test">
                {{-- 栏目管理 --}}
                <li class="layui-nav-item">
                    <a class="menu_articles" href="javascript:;" >栏目管理</a>
                    <dl class="layui-nav-child">
                        <dd id="article_menu_6"><a href="/admin/article/index/6#article_menu_6">商会章程</a></dd>
                        <dd id="article_menu_7"><a href="/admin/article/index/7#article_menu_7">商会架构</a></dd>
                        <dd id="article_menu_8"><a href="/admin/article/index/8#article_menu_8">商会公告栏</a></dd>
                    </dl>
                </li>

                {{-- <li class="layui-nav-item"><a href="/admin/audio_column/index#column_menu" id="column_menu" class="menu_column">专栏管理</a></li> --}}
            </ul>
          </div>
        </div>

        <div class="layui-body">
            <div style="padding: 15px;">
                @yield('content')
            </div>
        </div>

        <div class="layui-footer">
          <!-- 底部固定区域 -->
          © 南海青年商会
        </div>
</div>

@endsection


@push('body-scripts')
    <script>
        $(document).ready(function () {
            // 退出登录
            $('#logout_btn').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/logout',
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        location = '/login';
                    },
                });
            });

            // alert(location);
            // 高亮显示当前选中的菜单项
            $(location.hash).addClass('layui-this');
            //打开隐藏菜单项
            $(location.hash).parents('li.layui-nav-item').addClass('layui-nav-itemed');

            // Add icons to the menu item
            (function addIcons() {
                let addIcon = function (tParentId, icon) {
                    $i = '<i class="layui-icon layui-icon-'+icon+'"></i>';
                    $('.'+tParentId).prepend($i);
                };
                let iconConf = [
                    ['menu_das', 'link'],
                    ['menu_news_article', 'align-left'],
                    ['menu_activity', 'group'],
                    ['menu_video', 'video'],
                    ['menu_exproduct', 'about'],
                    ['menu_bp_shop', 'diamond'],
                    ['menu_shop', 'cart-simple'],
                    ['menu_seckill', 'rmb'],
                    ['menu_config', 'set-sm'],
                    ['menu_members', 'user'],
                    ['menu_apps', 'app'],
                    ['menu_column', 'headset']
                ];
                iconConf.forEach(el => {
                    addIcon(el[0], el[1]);
                });
            }());
        });
    </script>
@endpush
