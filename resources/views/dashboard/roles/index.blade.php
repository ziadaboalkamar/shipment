@extends('dashboard.layouts.app')

@section('title', __('site.' . $module_name_plural))


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.'.$module_name_plural)</h1>

            <ol class="breadcrumb">
                <li> <a href="{{ route('dashboard.home') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a>
                </li>
                <li class="active"><i class="fa fa-hourglass-half"></i> @lang('site.'.$module_name_plural)</li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-primary">

                <div class="box-header with-border">
                    <h1 class="box-title"> @lang('site.'.$module_name_plural) <small>{{ $rows->total() }}</small></h1>

                    <form action="{{ route('dashboard.' . $module_name_plural . '.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control"
                                    placeholder="@lang('site.search')">
                            </div>

                            <div class="col-md-4">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>
                                    @lang('site.search')</button>
                                    @if (auth()->user()->hasPermission('create-'.$module_name_plural))
                                    <a href="{{ route('dashboard.' . $module_name_plural . '.create') }}"
                                    class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                    @else
                                    <button class="btn btn-primary" disabled>@lang('site.add')</button>
                                    @endif
                              

                            </div>
                        </div>
                    </form>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    @if ($rows->count() > 0)

                        
                <table class="table table-hover">

                    <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>@lang('site.name')</th>
                                    <th>@lang('site.description')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($rows as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->description }}</td>
                                        <td>
                                            @if (auth()->user()->hasPermission('update-'.$module_name_plural))
                                                @include('dashboard.buttons.edit')
                                            @else
                                            <input class="btn btn-info btn-sm" type="submit" value="@lang('site.edit')" disabled>
                                            @endif

                                            @if (auth()->user()->hasPermission('delete-'.$module_name_plural))
                                            @include('dashboard.buttons.delete')
                                        @else
                                        <input class="btn btn-danger btn-sm" type="submit" value="@lang('site.delete')" disabled>

                                        @endif

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table> {{-- end of table --}}

                        {{ $rows->appends(request()->query())->links() }}

                    @else
                        <tr>
                            <h4>@lang('site.no_records')</h4>
                        </tr>
                    @endif

                </div> {{-- end of box body --}}

            </div> {{-- end of box --}}

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
