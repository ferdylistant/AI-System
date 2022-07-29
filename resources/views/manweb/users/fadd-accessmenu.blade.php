<div class="tab-pane fade show active" id="access" role="tabpanel" aria-labelledby="access-tab">
    <form id="form_UpdateAccess">
    <input type="hidden" name="user_id" value="{{$user->id}}">
    <div id="treeview_container" class="hummingbird-treeview" >
        <ul id="treeview" class="hummingbird-base">
            <!-- Access-Bagian -->
            @foreach($accBagian as $ab)
            <li>
                <i class="fa fa-minus"></i>
                <label>
                    <input id="xnode-{{$ab->id}}" data-id="custom-{{$ab->id}}" type="checkbox" {{$ab->checked?'checked':''}} /> {{$ab->name}}
                </label>
                <ul style="display: block;">
                    <!-- Access -->
                    @foreach($access['ls'] as $ls)
                    @if($ab->id === $ls->bagian_id)
                    <li>
                        <i class="fa fa-plus"></i>
                        <label>
                            <input id="xnode-{{$ab->id}}-{{$ls->id}}" data-id="custom-{{$ab->id}}-{{$ls->id}}" type="checkbox" {{$ls->checked?'checked':''}}/> {{$ls->name}}
                        </label>

                        @if($ls->url=='#')
                        <ul>
                            @foreach($access['ld'] as $ld)
                            <li>
                                <i class="fa fa-plus"></i>
                                <label>
                                    <input id="xnode-{{$ab->id}}-{{$ls->id}}-{{$ld->id}}" data-id="custom-{{$ab->id}}-{{$ls->id}}-{{$ld->id}}" type="checkbox" {{$ld->checked?'checked':''}}/> {{$ld->name}}
                                </label>

                                <ul>
                                    <!-- Permissions -->
                                    @foreach($permissions as $p)
                                    @switch($p->type)
                                        @case('Read') @php $iconld = 'fas fa-envelope-open-text'; @endphp @break
                                        @case('Create') @php $iconld = 'fas fa-plus-square'; @endphp @break
                                        @case('Update') @php $iconld = 'fas fa-edit'; @endphp @break
                                        @case('Delete') @php $iconld = 'fas fa-trash-alt'; @endphp @break
                                        @case('Approval') @php $iconld = 'fas fa-check-circle'; @endphp @break
                                        @default @php $iconld = 'fas fa-question-circle' @endphp
                                    @endswitch
                                    @if($ld->id == $p->access_id)
                                    <li>
                                        <i class="{{$iconld}}"></i>
                                        <label>
                                            <input id="xnode-{{$ab->id}}-{{$ls->id}}-{{$ld->id}}-{{$p->id}}" data-id="custom-{{$ab->id}}-{{$ls->id}}-{{$ld->id}}-{{$p->id}}" value="{{$p->id}}" name="access[]" type="checkbox" {{$p->checked?'checked':''}}/> {{$p->name}}
                                        </label>
                                    </li>
                                    @endif
                                    @endforeach

                                </ul>
                            </li>
                            @endforeach

                        </ul>
                        @else
                        <ul>
                            <!-- Permissions -->
                            @foreach($permissions as $p)
                            @switch($p->type)
                                @case('Read') @php $iconls = 'fas fa-envelope-open-text'; @endphp @break
                                @case('Create') @php $iconls = 'fas fa-plus-square'; @endphp @break
                                @case('Update') @php $iconls = 'fas fa-edit'; @endphp @break
                                @case('Delete') @php $iconls = 'fas fa-trash-alt'; @endphp @break
                                @case('Approval') @php $iconld = 'fas fa-check-circle'; @endphp @break
                                @default @php $iconls = 'fas fa-question-circle' @endphp
                            @endswitch
                            @if($ls->id == $p->access_id)
                            <li>
                                <i class="{{$iconls}}"></i>
                                <label>
                                    <input id="xnode-{{$ab->id}}-{{$ls->id}}-{{$p->id}}" data-id="custom-{{$ab->id}}-{{$ls->id}}-{{$p->id}}" value="{{$p->id}}" name="access[]" type="checkbox" {{$p->checked?'checked':''}}/> {{$p->name}}
                                </label>
                            </li>
                            @endif
                            @endforeach

                        </ul>
                        @endif
                    </li>
                    @endif
                    @endforeach
                </ul>
            </li>
            @endforeach
        </ul>
    </div>

    <button type="submit" class="btn btn-sm btn-warning float-right">Simpan</button>
    </form>
</div>
