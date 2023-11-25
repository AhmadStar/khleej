<h4>حاسبة العمر والزمن</h4>

<label for="birthdate">أدخل تاريخ ميلادك الميلادي:</label>
<input type="date" id="gregorianBirthdate">

{{--<label for="hijriBirthdate">أدخل تاريخ ميلادك الهجري:</label>--}}
{{--<input type="date" id="hijriBirthdate">--}}

<button @click="calculateAgeAndTime()">حساب العمر والزمن</button>

<p id="result"></p>