
<center>
    <div class='pagination'>
        <ul>
            @for ($i = 1; $i <= $pagination['last_page']; $i++)
            <li @if($i == $pagination['current_page']) class="active" @endif>
                <a href="{{ $url }}?page={{ $i }}">{{$i}}</a>
            </li>
            @endfor
        </ul>
    </div>
</center>