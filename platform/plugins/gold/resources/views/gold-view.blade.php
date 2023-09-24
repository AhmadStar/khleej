
<h2>{{$gold->label1}}</h2>
<h3>{{$gold->label2}}</h3>
<table class="rates table table-striped table-hover table-sm">
    <thead>
    <tr>
        <th>وحدة الذهب</th>
        <th>السعر الحالي</th>

    </tr>
    </thead>
    <tbody>
    <tr>
        <th>أوقية الذهب
        </th>
        <td>{{$gold->ounce}}</td>

    </tr>
    <tr>
        <th>جرام الذهب عيار 24
        </th>
        <td>{{$gold['24_karat']}}</td>

    </tr>
    <tr>
        <th>جرام الذهب عيار 22
        </th>
        <td>{{$gold['22_karat']}}</td>

    </tr>
    <tr>
        <th>جرام الذهب عيار 21
        </th>
        <td>{{$gold['21_karat']}}</td>

    </tr>
    <tr>
        <th>جرام الذهب عيار 18
        </th>
        <td>{{$gold['18_karat']}}</td>

    </tr>
    <tr>
        <th>جرام الذهب عيار 14
        </th>
        <td>{{$gold['14_karat']}}</td>

    </tr>
    </tbody>
</table>
<img class="alkhaleej-services" src="{{ RvMedia::getImageUrl($gold->chart, '', false, RvMedia::getDefaultImage()) }}" alt=" خدمات الخليج " />


{{--{!!$gold->html_table  !!}}--}}
{{--'ounce',--}}
{{--'chart',--}}
{{--'country',--}}
{{--'24_karat',--}}
{{--'label1',--}}
{{--'label2',--}}
{{--'22_karat',--}}
{{--'21_karat',--}}
{{--'18_karat',--}}
{{--'14_karat',--}}
{{--'html_table',--}}