/**
 * Created by Abdulhamid on 11/24/2023.
 */


var services = new Vue({

        el: '#FrontServices',

        data: {
            liveHost: window.liveHost,
            myHost: '',


        },

        created: function () {
            this.getHost();


        },

        watch: {},

        computed: {},

        methods: {
            getHost: function () {
                this.myHost = this.liveHost;
                if (location.host == 'localhost:8080')
                    this.myHost = 'http://localhost:8080/';
                return this.myHost;
            },

            calculateAgeAndTime: function () {
                // Get the input values (gregorian and hijri birthdates) from the user
                var gregorianBirthdate = document.getElementById("gregorianBirthdate").value;
                // var hijriBirthdate = document.getElementById("hijriBirthdate").value;

                // Create Date objects for the gregorian and hijri birthdates
                var gregorianBirthdateObj = new Date(gregorianBirthdate);
                // var hijriBirthdateObj = new Date(hijriBirthdate);

                // Get the current date
                var currentDate = new Date();

                // Calculate the difference in years for both gregorian and hijri
                var gregorianAge = currentDate.getFullYear() - gregorianBirthdateObj.getFullYear();
                // var hijriAge = currentDate.getFullYear() - hijriBirthdateObj.getFullYear();

                // Check if the birthdays haven't occurred yet this year
                if (currentDate.getMonth() < gregorianBirthdateObj.getMonth() || (currentDate.getMonth() === gregorianBirthdateObj.getMonth() && currentDate.getDate() < gregorianBirthdateObj.getDate())) {
                    gregorianAge--;
                }

                // if (currentDate.getMonth() < hijriBirthdateObj.getMonth() || (currentDate.getMonth() === hijriBirthdateObj.getMonth() && currentDate.getDate() < hijriBirthdateObj.getDate())) {
                //     hijriAge--;
                // }

                // Calculate the difference in hours and days
                var timeDifferenceHours = Math.abs(currentDate - gregorianBirthdateObj) / 36e5; // 1 hour = 36e5 milliseconds
                var timeDifferenceDays = Math.abs(currentDate - gregorianBirthdateObj) / 8.64e7; // 1 day = 8.64e7 milliseconds

                // Display the result
                var resultText = "العمر الميلادي: " + gregorianAge + " سنة" +
                    // "<br>العمر الهجري: " + hijriAge + " سنة" +
                    "<br>الفارق بالساعات: " + timeDifferenceHours.toFixed(2) + " ساعة" +
                    "<br>الفارق بالأيام: " + timeDifferenceDays.toFixed(2) + " يوم";
                document.getElementById("result").innerHTML = resultText;
                document.getElementById("result").innerHTML = resultText;
            },


        },
    })
    ;