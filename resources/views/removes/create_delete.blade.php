<?php
use App\Models\User;use App\Models\Post;use App\Models\Group;use App\Models\Group_user;use App\Models\Comment;
?>
@include('layouts.app')

@include('objects.'.$obj_name, [$obj_name => $object])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Törlés') }}</div>
                <div class="card-body">
                    <form class="d-inline" method="POST" action="{{ route('delete_create', ['obj_name' => $obj_name, 'id' => $id]) }}">
                    @csrf
                    
                    <div class="row mb-3">
                    <label for="delete_mode" class="col-md-4 col-form-label text-md-end">Törlés módja</label>
                <div class="col-md-7">
                    <select id="delete_mode" name="delete_mode" class="form-select" value="1" onchange="showDeletes()">
                        <option value="1">{{ __("Csak a kijelölt tartalmat") }}</option>
                        <option value="2">{{ __("Csak a közvetlenül kapcsolódó tartalmakat") }}</option>
                        <option value="3">{{ __("Minden kapcsolódó tartalmat") }}</option>   
                    </select>
                    <script>
                                    function showDeletes() {

                                    var item = document.getElementById("items");
                                    var selected = document.getElementById("delete_mode").value;

                                    var first = document.getElementById("first");  
                                    var referred = document.getElementById("referred");  
                                    var all = document.getElementById("all");

                                    first.style.display = "none";
                                    referred.style.display = "none";
                                    all.style.display = "none";

                                    if (selected == 1) first.style.display = "";
                                    if (selected == 2) referred.style.display = "";
                                    if (selected == 3) all.style.display = "";
                                                                   
                                    }</script>
                </div>
            </div>
            
                         <br>
                         
                        <label>A következő tartalmak törlésére készülsz:</label>
                            
                            <div id="first"style="display:">
                            <?php $i = 0;?>     
                         @foreach ($first as $item)                                           
                             <li>{{$item}} {{__(array_keys($first)[$i])}}</li><?php $i++; ?>
                         @endforeach
                         </div>

                         <div id="referred"style="display:none">  
                         <?php $i = 0;?>   
                         @foreach ($referred as $item)                                           
                             <li>{{$item}} {{__(array_keys($referred)[$i])}}</li><?php $i++; ?>
                         @endforeach
                         </div>

                         <div id="all"style="display:none"> 
                         <?php $i = 0;?>    
                         @foreach ($all as $item)                                           
                             <li>{{$item}} {{__(array_keys($all)[$i])}}</li><?php $i++; ?>
                         @endforeach
                         </div>
                     <br>
                        <button type="submit" class="btn btn-primary">{{ __('Tartalom törlése') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

