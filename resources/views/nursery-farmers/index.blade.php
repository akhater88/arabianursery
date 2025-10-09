@php use App\Models\SeedlingService; @endphp
@extends('layouts.dashboard')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="form-row mb-3">
                        <div class="col-12 col-sm-4">
                            <label for="farm-user-name">اسم العميل</label>
                            <input id='farm-user-name' type="text" name="farm_user_name"
                                   value="{{ request('farm_user_name') }}"
                                   class="form-control">
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="farm-user-phone-number">رقم الهاتف</label>
                            <input type="text" name="farm_user_phone_number"
                                   value="{{ request('farm_user_phone_number') }}"
                                   class="form-control" id="farm-user-phone-number">
                        </div>


                    </div>


                    <div class="form-group">
                        <button type="submit"
                                class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                            بحث
                        </button>

                        <a href="{{route('nursery-farmers')}}" class="btn btn-primary float-right col-12 col-sm-3 col-md-2 col-lg-2 col-xl-1 mt-2 mt-sm-0 mr-0 mr-sm-2" > مسح</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>الاسم المزارع</th>
                                    <th>رقم الهاتف</th>
                                    <th> عدد الصواني خدمة التشتيل </th>
                                    <th> عدد الصواني شراء </th>
                                    <th>مجموع الدفعات</th>
                                    <th>مدفوع</th>
                                    <th>متبقي</th>
                                    <th>العمليات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($farmers as $farmer)
                                    <tr>
                                        <td>{{$farmer->id}}</td>
                                        <td>{{$farmer->name}}</td>
                                        <td>{{$farmer->country_code.$farmer->mobile_number}}</td>
                                        <td>
                                            @if(key_exists($farmer->id,$sumSeedlingTrayByFarmer))
                                            {{$sumSeedlingTrayByFarmer[$farmer->id]}}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if(key_exists($farmer->id,$sumSeedlingPurchaseTrayByFarmer))
                                                {{$sumSeedlingPurchaseTrayByFarmer[$farmer->id]}}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if(key_exists($farmer->id,$sumPaidInstalments) && key_exists($farmer->id,$sumNotPaidInstalments))
                                                {{$sumPaidInstalments[$farmer->id]+$sumNotPaidInstalments[$farmer->id]}}
                                            @elseif(key_exists($farmer->id,$sumPaidInstalments))
                                                {{$sumPaidInstalments[$farmer->id]}}
                                            @elseif(key_exists($farmer->id,$sumNotPaidInstalments))
                                                {{$sumNotPaidInstalments[$farmer->id]}}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if(key_exists($farmer->id,$sumPaidInstalments) )
                                                {{$sumPaidInstalments[$farmer->id]}}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if(key_exists($farmer->id,$sumNotPaidInstalments) )
                                                {{$sumNotPaidInstalments[$farmer->id]}}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            <div class="col-12 d-flex flex-wrap" style="min-width:220px; gap:0.5rem;">
                                                <a class="btn btn-info" href="{{ route('nursery-operators.edit', $farmer->id) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-outline-primary"
                                                   target="_blank"
                                                   rel="noopener"
                                                   href="{{ route('nursery-farmers.reports.season', ['farmer' => $farmer->id, 'season_id' => 'all']) }}">
                                                    <i class="fas fa-print"></i>
                                                    <span class="d-none d-md-inline">تقرير الموسم</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection

