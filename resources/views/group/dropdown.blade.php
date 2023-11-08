<?php use App\Models\Role; use App\Models\Status; use App\Models\User; ?>
<td rowspan="2" style = "width:250px;border:0;vertical-align:middle">
    
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                Műveletek
            </a>
        <div class="dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdown">                                             
            <a class="dropdown-item" href="/group/edit/{{$group->id}}">Szerkesztés</a>
            <a class="dropdown-item" href="/group/delete/{{$group->id}}">Törlés</a>

             @if ($isAdmin && !$isOwner)
             <hr class="dropdown-divider">
            <a class="dropdown-item" href="/group/remove/{{$group->id}}">Tiltás</a>
            @endif
        </div>
        
    </td>
