@inject('logged', 'App\Http\Controllers\Controller')
<?php use App\Models\Role; use App\Models\Status; use App\Models\User; ?>
<td rowspan="2" style = "width:250px;border:0;vertical-align:middle">
    
<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                Műveletek
            </a>
        <div class="dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdown">

        @if($isLogged)
        <a class="dropdown-item" href="/post/edit/{{$post->id}}">Szerkesztés</a>
        @endif

@if($logged->hasRight('remove post', $post->id) && !$isLogged)  
 
        @if($logged->hasRight('remove post', $post->id))
                <a class="dropdown-item" href="/post/remove/{{$post->id}}">Tiltás</a>
        @endif

@elseif($isLogged && $post -> status -> name != 'removed')
        <a class="dropdown-item" href="/post/delete/{{$post->id}}">Törlés</a>
@endif
        </div>
        
    </td>
