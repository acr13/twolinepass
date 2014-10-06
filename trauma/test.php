<!DOCTYPE html>
<html lang="en">
    <body>
        <script src="../assets/js/jquery.js"></script>
        <script src="../dist/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function()
            {
                /*
                $.ajax(
                {
                    url: "http://production.emsstaffpro.com/login.php",
                    type: 'POST',
                    data: {
                        action: 'login',
                        username: 'Shawn',
                        password: '9e60b53d0e048e84898bee52614a249b6c1683d2'
                    },
                    dataType: 'json',
                    complete: function(xhr, data)
                    {
                        console.log('log in complete');
                        console.log(data);
                    }
                });
                */
                
               $.ajax({
                    url:"https://production.emsstaffpro.com/api/mobile/TripViewer/TripLegs",
                    type:"POST",
                    data: {
                        'pickup_time': '2014-12-01 13:00',
                        'patient_id': 300803,
                        'ordering_facility_id': 843, // facility id of logged in user
                        'pu_facility_id': 7203, // pickup facility
                        'pu_facility_name': 'Bronson Advanced Radiology **PC**',
                        'pu_room': '34',
                        'pu_address1': '524 S PARK ST',
                        'pu_city': 'KALAMAZOO',
                        'pu_state': 'MI',
                        'pu_zipcode': '49001',
                        'do_facility_id': 1061, // dropoff facility
                        'do_facility_name': 'Facility 2',
                        'do_address1': '6065 Gull Rd',
                        'do_city': 'Kalamazoo',
                        'do_state': 'MI',
                        'do_zipcode': '49048',
                        'call_type_id': 40,
                        'oxygen': '1', // 1 or 0
                        'cardiac_monitor': '0', // 1 or 0
                        'iv': '0', // 1 or 0
                        'ventilator': '0', // 1 or 0,
                        'comments': 'testing...'
                    },
                    dataType: "json",
                    complete: function( xhr, data ) {
                        console.log(xhr.responseText);
                    }
                });
                
            });
        </script>
    </body>
</html>