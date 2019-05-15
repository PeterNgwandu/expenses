
<script type="text/javascript" src="{{url('assets/js/jquery.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/bootstable.js')}}"></script>
<script src="{{url('assets/vendor/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{url('js/bootstrap-confirm-delete.js')}}"></script>
<!-- <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
 -->
    <!-- Bootstrap -->
    <script src="{{url('assets/vendor/popper.js')}}"></script>
    <script src="{{url('assets/vendor/bootstrap.min.js')}}"></script>
    <script src="{{url('assets/vendor/Chart.min.js')}}"></script>
    <script src="{{url('assets/vendor/moment.min.js')}}"></script>
    <script src="{{url('assets/vendor/dateformat.js')}}"></script>

    <script src="{{url('assets/js/app.js')}}"></script>
    <script>
        window.theme = "default";
    </script>
    <script src="{{url('assets/js/color_variables.js')}}"></script>


    <script src="{{url('assets/vendor/Chart.min.js')}}"></script>
    <script src="{{url('assets/vendor/morris.min.js')}}"></script>
    <script src="{{url('assets/vendor/raphael.min.js')}}"></script>
    <script src="{{url('assets/vendor/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{url('assets/js/datepicker.js')}}"></script>




    <script src="{{url('assets/vendor/dom-factory.js')}}"></script>
    <!-- DOM Factory -->
    <script src="{{url('assets/vendor/material-design-kit.js')}}"></script>
    <!-- MDK -->



    <script>
        (function() {
            'use strict';

            // Self Initialize DOM Factory Components
            domFactory.handler.autoInit()

            // Connect button(s) to drawer(s)
            var sidebarToggle = Array.prototype.slice.call(document.querySelectorAll('[data-toggle="sidebar"]'))

            sidebarToggle.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    var selector = e.currentTarget.getAttribute('data-target') || '#default-drawer'
                    var drawer = document.querySelector(selector)
                    if (drawer) {
                        drawer.mdkDrawer.toggle()
                    }
                })
            })

        })();

    </script>

     <script src="{{url('assets/vendor/jquery.dataTables.js')}}"></script>
    <script src="{{url('assets/vendor/dataTables.bootstrap4.js')}}"></script>

    <script>
        $('#data-table').dataTable();
    </script>
    <script type="text/javascript">
//         Swal.fire({
//   position: 'top-end',
//   type: 'success',
//   title: 'Your work has been saved',
//   showConfirmButton: false,
//   timer: 1500
// })

    </script>
@include('sweet::alert')
