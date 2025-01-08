<section class="content-header">
  <h1>
    Data User
    <small>Template</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">User</a></li>
    <li class="active">Data User</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">List User</h3>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table id="example1" class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Username</th>
                <th>Real Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Level</th>
                <th>Company</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>
  $(function() {
    $("#example1").DataTable({
      stateSave: true
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
</body>

</html>