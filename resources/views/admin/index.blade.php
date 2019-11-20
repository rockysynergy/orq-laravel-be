@extends('layouts.admin.admin')


@section('content')
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md8">
            <div class="layui-col-md12">
                <div class="section-wrapper">
                    <div class="layui-card">
                        <div class="layui-card-header">概览</div>
                        <div class="layui-card-body">
                            会员数：32 <br>
                            发表文章： 12<br>
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12">
                <div class="section-wrapper">
                    <div class="layui-row layui-col-space15">
                        <div class="layui-col-md6">
                            <div class="layui-card">
                                <div class="layui-card-header">文章浏排行榜</div>
                                <div class="layui-card-body">
                                    <ul>
                                        <li>文章标题</li>
                                        <li>文章标题</li>
                                        <li>文章标题</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md6">
                            <div class="layui-card">
                                <div class="layui-card-header">活动排行榜</div>
                                <div class="layui-card-body">
                                    <ul>
                                        <li>活动标题</li>
                                        <li>活动标题</li>
                                        <li>活动标题</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('body-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: ['1月', '2月', '3月', '4月', '5月', '6月', '7月'],
                datasets: [{
                    label: '会员人数',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: [0, 10, 5, 2, 20, 30, 45]
                }]
            },

            // Configuration options go here
            options: {}
        });
    </script>
@endpush
