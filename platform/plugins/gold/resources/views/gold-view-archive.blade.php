


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

<div class="table-responsive">
    <table class="table table-sm table-hover table-striped table-reponsive table-bordered">
        <thead>
        <tr>
            <th>التاريخ</th>
            <th>أوقية الذهب</th>
            <th>جرام الذهب عيار 24</th>
            <th>جرام الذهب عيار 22</th>
            <th>جرام الذهب عيار 21</th>
            <th>جرام الذهب عيار 18</th>
            <th>جرام الذهب عيار 14</th>
        </tr>
        </thead>
        <tbody>
        @foreach($goldsRecords as $goldsRecord)
        <tr>
            @php
            $fmt = new \IntlDateFormatter('ar', null, null);
            $fmt->setPattern('cccc, dd. MMMM YYYY');
            $date = strtotime($goldsRecord->created_at);

            $date=  $fmt->format($date);
            @endphp
            <th nowrap="nowrap">{{$date}}</th>
            <td>{{$goldsRecord->ounce}}</td>
            <td>{{$goldsRecord['24_karat']}}</td>
            <td>{{$goldsRecord['22_karat']}}</td>
            <td>{{$goldsRecord['21_karat']}}</td>
            <td>{{$goldsRecord['18_karat']}}</td>
            <td>{{$goldsRecord['14_karat']}}</td>
        </tr>
            @endforeach

        </tbody>
    </table>
</div>
<br>
<h3 class="question">اونصة الذهب تساوي كم جرام؟</h3>
<p>
الأونصة هي إحدى وحدات قياس الكتلة، وهي مستخدمة في عدد من الأنظمة المختلفة لوحدات القياس وتساوي 28,349523125 جرام. لكن بالنسبة للأونصة كوحدة قياس المعادن النفيسة فتساوي 31.1034768 جرام وتسمي أونصة تروي.
</p><br>
<h3 class="question">كم يبلغ سعر غرام الذهب اليوم؟</h3>
<p>جرام الذهب عيار 24
<br>{{$gold['24_karat']}}<br>
</p><br>

<h3 class="question">كم اونصة الذهب في الكيلو؟</h3>
<p>	32.1507466</p>
<br>
<h3 class="question">
ما هو الفرق بين الاونصة والاوقية؟
</h3>
<p>	ووحدة واحدة من الأوقية تساوي 480 حبة، والوزن الدقيق للأوقية الدولية يساوي 31.1034768 جرامًا، ويعادل أوقية تروي من الذهب 31.1034807 جرامًا. كما تستخدم الأوقية أيضًا لقياس كتلة السوائل، وتعادل أونصة سائلة 28.4 مل تقريبًا في النظام الإمبراطوري أو 29.6 مل تقريبًا في نظام الولايات المتحدة.
</p>