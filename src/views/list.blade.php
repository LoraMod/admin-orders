@extends('admincore::layouts.dashboard')

@section('content')

    <div id="page-wrapper" data-ng-app="App" data-ng-controller="ordersController">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Orders</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Names</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $order)
                            <tr>
                                <td>
                                    {{$order->id}}
                                </td>
                                <td>
                                    {{$order->names }}
                                </td>
                                <td>
                                    {{$order->status}}
                                </td>
                                <td>
                                    {{$order->created_at->format('d.m.Y H:i')}}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-xs" title="Preview" data-ng-click="previewOrder({{$order->id}})"><i class="fa fa-eye"></i></button>
                                    <a href="{{route('admin.orders.form', ['id' => $order->id])}}" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                                    <a href="{{route('admin.orders.delete', ['id' => $order->id])}}" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6">{{$items->links()}}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="previewOrder">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Preview order #@{{ order.id }} | @{{ order.status }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <address>
                                    <p>
                                        <strong>@{{ order.names }}</strong>
                                    </p>
                                    <p>
                                        @{{ order.phone }}
                                    </p>
                                    <p>
                                        @{{ order.email }}
                                    </p>

                                </address>
                            </div>
                            <div class="col-md-4">
                                <strong>Delivery</strong><br/>
                                <p>
                                    @{{ order.city }} @{{ order.country }}
                                </p>
                                <p>
                                    @{{ order.address }}
                                </p>

                            </div>
                            <div class="col-md-4">
                                <strong>Info</strong><br/>
                                <p>
                                    Payment method: @{{ order.payment_method }}
                                </p>
                                <p>
                                    Date: @{{ order.created_at }}
                                </p>
                                <p data-ng-if="order.notes">
                                Notes:<br/>
                                @{{ order.notes }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>QTY</th>
                                        <th>TOTAL</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr data-ng-repeat="item in order.items">
                                        <td>@{{ item.product_id }}</td>
                                        <td>@{{ item.product_name }}</td>
                                        <td>@{{ item.price | currency }}</td>
                                        <td>@{{ item.qty | number:3 }}</td>
                                        <td>@{{ item.qty * item.price | currency}}</td>
                                    </tr>


                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right">Discount</th>
                                            <td>@{{ -0 | currency }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right">Delivery</th>
                                            <td>@{{ 0 | currency }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="text-right">Total</th>
                                            <td data-ng-bind="orderTotal() | currency "></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <script>

        app.controller('ordersController', function($scope, $http, SweetAlert, CSRF_TOKEN, $window) {
            $scope.order = {};

            $scope.orderTotal = function(){
                var total = 0;
                angular.forEach($scope.order.items, function(item, $index){
                    total+=item.price*item.qty;
                });
                return total;
            };

            $scope.previewOrder = function(order_id) {
                $http.get('/admin/orders/form', {
                    params: {
                        id: order_id
                    }
                })
                    .then(function(response){
                        $scope.order = response.data.item;
                        $('#previewOrder').modal('show');
                    })
            }
        });
    </script>
@stop
@section('css')
    <script>
        var app = angular.module('App', ['ui.bootstrap', 'ngSanitize', 'oitozero.ngSweetAlert']);
        app.run(['$http', 'CSRF_TOKEN', function($http, CSRF_TOKEN) {
            $http.defaults.headers.common['X-Csrf-Token'] = CSRF_TOKEN;
        }]);
    </script>
@stop