@extends('layouts.admin.admin')

@section('content')
    <div class="content__pg-admin">
        <nav class="nav__top">
            <div class="nav-item nav-item__new">
                <button type="button" class="layui-btn" id="add-admin"><i class="layui-icon layui-icon-add-1"></i>添加</button>
            </div>
        </nav>
        <div id="admin-list-table" lay-filter="admin-list-table"></div>
    </div>

    {{-- 文章列表toolbar 的模板 --}}
    <script type="text/html" id="admin-actions">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn  layui-btn-danger layui-btn-xs" lay-event="deactivate">禁用</a>
    </script>
@endsection

@push('body-scripts')
<script>
        let appId = $('#appId').val();
        layui.use(['layer', 'form', 'table'], function () {
            let layer = layui.layer;
            let form = layui.form;
            let table = layui.table;
            let element = layui.element;

            // 管理员表格
            let adminListTable = table.render({
                elem: '#admin-list-table'
                , url: '/admin/admin/index/?fetchData=1&app_id=' + appId //数据接口
                , page: true //开启分页
                , width: 500
                , limit: 15
                , cols: [[
                    { field: 'id', title: 'ID', width: 70, sort: true, }
                    , { field: 'name', title: '姓名', width: 200 }
                    // , { align: 'center', toolbar: '#admin-actions' }
                    , { align: 'center', templet: function (d) {
                        let s = '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>';
                        if (d.deleted_at) {
                            s += '<a class="layui-btn  layui-btn-danger layui-btn-xs" lay-event="restore">启用</a>';
                        } else {
                            s += '<a class="layui-btn  layui-btn-danger layui-btn-xs" lay-event="deactivate">禁用</a>';
                        }

                        return s;
                    }}
                ]]

            });

            // 监听管理员相关的事件
            table.on('tool(admin-list-table)', function (obj) {
                var data = obj.data; //获得当前行数据
                var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                var tr = obj.tr; //获得当前行 tr 的DOM对象

                if (layEvent === 'deactivate') { //删除
                    layer.confirm('禁用后该用户将无法登录！真的禁用吗？', function (index) {
                        $.ajax({
                            url: '/admin/admin/deactivate',
                            data: { admin_id: data.id },
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (res) {
                                layer.close(index);
                                if (res.code == 0) {
                                    adminListTable.reload();
                                } else if (res.code == 1) {
                                    alert(res.msg);
                                }
                            }
                        });
                    });
                } else if (layEvent === 'edit') { //编辑
                    showAdminForm('edit', data.id);
                } else if (layEvent === 'restore') { //编辑
                    layer.confirm('启用后该用户将可以登录后台！真的启用吗？', function (index) {
                        $.ajax({
                            url: '/admin/admin/restore',
                            data: { admin_id: data.id },
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (res) {
                                layer.close(index);
                                if (res.code == 0) {
                                    adminListTable.reload();
                                } else if (res.code == 1) {
                                    alert(res.msg);
                                }
                            }
                        });
                    });
                }
            });

            // 响应添加或修改管理员按钮点击事件
            $('#add-admin').on('click', function (e) {
                showAdminForm('new');
            })

            // 显示添加、编辑表单
            let showAdminForm = function showAdminForm(mode, admin_id = 'nil') {
                let url = '/admin/admin/' + mode + '?app_id=' + appId;
                if (!isNaN(parseInt(admin_id))) {
                    url += '&admin_id=' + admin_id;
                }

                layer.open({
                    type: 2,
                    content: url,
                    area: ['500px', '500px'],
                    title: '管理员',
                    cancel: function (index, layero) {
                        if (confirm('确定要关闭么')) {
                            layer.close(index)
                        }
                        return false;
                    },
                    end: function () {
                        adminListTable.reload();
                    }
                });
            };
        });

    </script>
@endpush
