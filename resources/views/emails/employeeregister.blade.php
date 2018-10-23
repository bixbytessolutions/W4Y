<div>
    Hi, {{ $data->toname }} <br/>
    <p> Please click on the below link and complete the registration. </p>
   
    <a href="{{ url('/employeeuserregister/'.$data->regtoken) }}">Click Here </a> <br>

    regards, <br>
    {{ $data->fromname }} 
</div>