@if ($group -> visibility -> name == "private")
    <div class="alert alert-danger">
    <strong> Ez egy privát csoport!</strong>
    </div>
@elseif($group -> new_member_status == null)
    <div class="alert alert-danger">
    <strong> Nem lehet jelentkezni ebbe a csoportba!</strong>
    </div>
@else
    <div class="alert alert-warning">
    <strong> Még nem vagy tag!</strong>
    <br> A posztok megtekintéséhez jelentkezz a csoportba!
    </div>

    <center>
    <b><a href="/group/join/{{$group->id}}">
    @if ($group -> new_member_status -> name == 'active')
        <button class="btn btn-primary">Belépés</button>
    @elseif ($group -> new_member_status -> name == 'pending')
        <button class="btn btn-primary">Jelentkezés küldése</button>
    @endif
    </a></b>
    </center>
@endif
