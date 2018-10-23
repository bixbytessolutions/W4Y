<div>
    Hi, {{ $data->toname }} <br/>
    <p> Please click on the below link to accept Invitation. </p>
   
    <a href="{{ url('/innviteupdate/'.$data->regtoken.'/'.$data->cmpId) }}">Click Here </a> <br>

    regards, <br>
    {{ $data->fromname }} 
</div>