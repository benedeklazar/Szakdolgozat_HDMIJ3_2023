@inject('logged', 'App\Http\Controllers\Controller')
<?php use App\Models\Role; use App\Models\Status; use App\Models\User; ?>
<td rowspan="2" style = "width:250px;border:0;vertical-align:middle">
    
        @if(($logged->hasRight('remove group_user', $group_user->id)
        || $isAdmin || $isOwner) && !$isLogged)  
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                Műveletek
            </a>
        <div class="dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdown">

        @if($logged->hasRight('edit group_user', $group_user->id))
                <a class="dropdown-item" href="/member/edit/{{$group_user->id}}">Szerkesztés</a>
                @if ($group_user -> status -> name == "pending")
                  <a class="dropdown-item" href="/member/admiss/{{$group_user->id}}">Beengedés</a>
                @endif
        @endif
                <a class="dropdown-item" href="/member/kick/{{$group_user->id}}">Kirúgás</a>
 
                <hr class="dropdown-divider">
                <a class="dropdown-item" href="/member/ban/{{$group_user->id}}">Bannolás</a>
 

        @elseif($isLogged && $group_user -> status -> name != 'ban' && $group_user -> group != null)
            <a class="dropdown-item" href="/group/left/{{$group_user->id}}">
            <button class="btn btn-danger">Kilépés</button></a>
        @endif
        </div>
        
    </td>
