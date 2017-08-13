@extends('backend.layouts.main')

@section('page_heading', 'Chính Sách Cộng Tác Viên')

@section('section')

    <form action="{{ action('Backend\SettingController@adminSettingCollaborator') }}" method="post">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">{{ $settings[\App\Models\Setting::COLLABORATOR_SILVER]->name }}</h3>
            </div>
            <div class="box-body">
                <?php
                $settingValue = json_decode($settings[\App\Models\Setting::COLLABORATOR_SILVER]->value, true);
                $settingValue[\App\Models\Collaborator::REVENUE_ATTRIBUTE] = \App\Libraries\Helpers\Utility::formatNumber($settingValue[\App\Models\Collaborator::REVENUE_ATTRIBUTE]);
                $settingValue = old(\App\Models\Setting::COLLABORATOR_SILVER, $settingValue);
                ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_SILVER . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Giảm Giá Được Tạo <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_SILVER }}[{{ \App\Models\Collaborator::DISCOUNT_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::DISCOUNT_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">%</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_SILVER . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_SILVER . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_SILVER . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Hoa Hồng Được Hưởng <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_SILVER }}[{{ \App\Models\Collaborator::COMMISSION_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::COMMISSION_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">%</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_SILVER . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_SILVER . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_SILVER . '.' . \App\Models\Collaborator::REVENUE_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Doanh Thu Lên Cấp <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control InputForNumber" name="{{ \App\Models\Setting::COLLABORATOR_SILVER }}[{{ \App\Models\Collaborator::REVENUE_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::REVENUE_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">VND</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_SILVER . '.' . \App\Models\Collaborator::REVENUE_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_SILVER . '.' . \App\Models\Collaborator::REVENUE_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">{{ $settings[\App\Models\Setting::COLLABORATOR_GOLD]->name }}</h3>
            </div>
            <div class="box-body">
                <?php
                $settingValue = json_decode($settings[\App\Models\Setting::COLLABORATOR_GOLD]->value, true);
                $settingValue[\App\Models\Collaborator::REVENUE_ATTRIBUTE] = \App\Libraries\Helpers\Utility::formatNumber($settingValue[\App\Models\Collaborator::REVENUE_ATTRIBUTE]);
                $settingValue = old(\App\Models\Setting::COLLABORATOR_GOLD, $settingValue);
                ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_GOLD . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Giảm Giá Được Tạo <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_GOLD }}[{{ \App\Models\Collaborator::DISCOUNT_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::DISCOUNT_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">%</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_GOLD . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_GOLD . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_GOLD . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Hoa Hồng Được Hưởng <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_GOLD }}[{{ \App\Models\Collaborator::COMMISSION_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::COMMISSION_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">%</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_GOLD . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_GOLD . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_GOLD . '.' . \App\Models\Collaborator::REVENUE_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Doanh Thu Lên Cấp <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control InputForNumber" name="{{ \App\Models\Setting::COLLABORATOR_GOLD }}[{{ \App\Models\Collaborator::REVENUE_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::REVENUE_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">VND</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_GOLD . '.' . \App\Models\Collaborator::REVENUE_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_GOLD . '.' . \App\Models\Collaborator::REVENUE_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">{{ $settings[\App\Models\Setting::COLLABORATOR_DIAMOND]->name }}</h3>
            </div>
            <div class="box-body">
                <?php
                $settingValue = json_decode($settings[\App\Models\Setting::COLLABORATOR_DIAMOND]->value, true);
                $settingValue = old(\App\Models\Setting::COLLABORATOR_DIAMOND, $settingValue);
                ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_DIAMOND . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Giảm Giá Được Tạo <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_DIAMOND }}[{{ \App\Models\Collaborator::DISCOUNT_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::DISCOUNT_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">%</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_DIAMOND . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_DIAMOND . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_DIAMOND . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Hoa Hồng Được Hưởng <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_DIAMOND }}[{{ \App\Models\Collaborator::COMMISSION_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::COMMISSION_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">%</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_DIAMOND . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_DIAMOND . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_DIAMOND . '.' . \App\Models\Collaborator::RERANK_TIME_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Thời Hạn Xét Lại Cấp <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_DIAMOND }}[{{ \App\Models\Collaborator::RERANK_TIME_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::RERANK_TIME_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">Tháng</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_DIAMOND . '.' . \App\Models\Collaborator::RERANK_TIME_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_DIAMOND . '.' . \App\Models\Collaborator::RERANK_TIME_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">{{ $settings[\App\Models\Setting::COLLABORATOR_MANAGER]->name }}</h3>
            </div>
            <div class="box-body">
                <?php
                $settingValue = json_decode($settings[\App\Models\Setting::COLLABORATOR_MANAGER]->value, true);
                $settingValue = old(\App\Models\Setting::COLLABORATOR_MANAGER, $settingValue);
                ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Giảm Giá Được Tạo <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_MANAGER }}[{{ \App\Models\Collaborator::DISCOUNT_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::DISCOUNT_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">%</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::DISCOUNT_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Hoa Hồng Được Hưởng <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_MANAGER }}[{{ \App\Models\Collaborator::COMMISSION_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::COMMISSION_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">%</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::COMMISSION_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::COMMISSION_DOWNLINE_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Hoa Hồng Được Hưởng Từ CTV Dưới Quyền <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_MANAGER }}[{{ \App\Models\Collaborator::COMMISSION_DOWNLINE_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::COMMISSION_DOWNLINE_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">%</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::COMMISSION_DOWNLINE_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::COMMISSION_DOWNLINE_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::DISCOUNT_DOWNLINE_SET_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Giảm Giá Được Thiết Lập Cho CTV Dưới Quyền <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_MANAGER }}[{{ \App\Models\Collaborator::DISCOUNT_DOWNLINE_SET_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::DISCOUNT_DOWNLINE_SET_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">%</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::DISCOUNT_DOWNLINE_SET_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::DISCOUNT_DOWNLINE_SET_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group{{ $errors->has(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::COMMISSION_DOWNLINE_SET_ATTRIBUTE) ? ' has-error': '' }}">
                            <label>Mức Hoa Hồng Được Thiết Lập Cho CTV Dưới Quyền <i>(bắt buộc)</i></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="{{ \App\Models\Setting::COLLABORATOR_MANAGER }}[{{ \App\Models\Collaborator::COMMISSION_DOWNLINE_SET_ATTRIBUTE }}]" value="{{ $settingValue[\App\Models\Collaborator::COMMISSION_DOWNLINE_SET_ATTRIBUTE] }}" required="required" />
                                <span class="input-group-addon">%</span>
                            </div>
                            @if($errors->has(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::COMMISSION_DOWNLINE_SET_ATTRIBUTE))
                                <span class="help-block">{{ $errors->first(\App\Models\Setting::COLLABORATOR_MANAGER . '.' . \App\Models\Collaborator::COMMISSION_DOWNLINE_SET_ATTRIBUTE) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop