@extends('layouts.admin.layer')

@section('content')
@if (isset($appId))
    <input type="hidden" value="{{$appId}}" id="appId" />
@endif
<div class="content__pg-config-edit layer-content-wrapper">
    {{-- 会费信息表单 --}}
    <form class="layui-form form__admin" action="" id="admin-form">
        <input type="hidden" value="{{isset($admin) ? $admin->id : ''}}" name="id">

        <div class="layui-form-item">
            <label class="layui-form-label">手机号码</label>
            <div class="layui-input-block">
                <input type="text" name="mobile" id="mobile" placeholder="请输入手机号码" lay-verify="required|phone" autocomplete="off" class="layui-input" value="{{isset($admin) ? $admin->user->mobile : ''}}">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">电子邮件</label>
            <div class="layui-input-block">
                <input type="text" name="email" id="email" placeholder="请输入电子邮件" lay-verify="email" autocomplete="off" class="layui-input" value="{{isset($admin) ? $admin->user->email : ''}}">
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input type="password" name="password" class="layui-input" id="password" lay-verify="required|password" value="{{isset($admin) ? 'TheDefault' : ''}}" />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">姓名</label>
            <div class="layui-input-block">
                <input type="text" lay-verify="name"  name="name" id="name" value="{{isset($admin) ? $admin->user->name : ''}}" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">权限</label>
            <div class="layui-input-block">
                <input type="checkbox" name="permissions[]" title="栏目管理" value="article_manage" {{isset($admin) && $admin->user->getPermissionNames()->contains('article_manage') ? 'checked' : ''}}>
                <input type="checkbox" name="permissions[]" title="俱乐部管理" value="club_manage" {{isset($admin) && $admin->user->getPermissionNames()->contains('club_manage') ? 'checked' : ''}}>
                <input type="checkbox" name="permissions[]" title="活动管理" value="activity_manage" {{isset($admin) && $admin->user->getPermissionNames()->contains('activity_manage') ? 'checked' : ''}}>
                <input type="checkbox" name="permissions[]" title="图片管理" value="picture_manage" {{isset($admin) && $admin->user->getPermissionNames()->contains('picture_manage') ? 'checked' : ''}}>
                <input type="checkbox" name="permissions[]" title="积分商城管理" value="bpshop_manage" {{isset($admin) && $admin->user->getPermissionNames()->contains('bpshop_manage') ? 'checked' : ''}}>
            </div>
        </div>

        <input class="layui-btn layui-btn-fluid layui-btn-normal " lay-submit="" type="button" value="提交" lay-filter="admin-submit" id="btn-submit">
    </form>
</div>
@endsection

@push('body-scripts')
    <script>
        layui.use(['form', 'layer'], function () {
            let form = layui.form;
            form.render();

            form.verify({
                password: function (value) {
                    if (value.length < 8) {
                        return '请输入最小长度为8、由字母、数字和符号组成的字符串'
                    }
                }
            });

            form.on('submit(admin-submit)', function(data){
                let tForm = document.getElementById('admin-form')
                let formData = new FormData(tForm);
                let id = formData.get('id');
                let url = "/admin/admin/";
                url += id > 0 ? 'update' : '';

                $.ajax({
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        // console.log(res);
                        if (res.code == 0) {
                            let index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                            parent.layer.close(index); //再执行关闭
                        } else if (res.code == 1) {
                            showError(tForm, res.msg);
                        } else if (res.code == 2) {
                            alert(res.msg);
                        }
                    },
                })

            });
        });
    </script>
@endpush
