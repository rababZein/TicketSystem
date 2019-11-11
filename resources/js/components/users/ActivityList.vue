<template>
  <div class="col-md-9">
    <div class="card">
      <div class="card-header p-2">
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a class="nav-link active" href="#activity" data-toggle="tab">Activity</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#projects" data-toggle="tab">Projects</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#tickets" data-toggle="tab">tickets</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#tasks" data-toggle="tab">tasks</a>
          </li>
        </ul>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <div class="tab-content">
          <div class="active tab-pane" id="activity">
            <ul class="panel-body list-group" style="height:276px;overflow-y:auto;">
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 9, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 7, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 7, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 7, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 7, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 7, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 6, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 6, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 6, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 6, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 6, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 6, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 1, 2019:</span>
                admin created client
                <a href="#">client2</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 1, 2019:</span>
                admin updated task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Oct 1, 2019:</span>
                admin created task
                <a href="#">test 2...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Sep 25, 2019:</span>
                admin updated task
                <a href="#">tssssss...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Sep 25, 2019:</span>
                admin updated task
                <a href="#">tssssss...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Sep 25, 2019:</span>
                admin updated task
                <a href="#">tssssss...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Sep 25, 2019:</span>
                admin updated task
                <a href="#">tssssss...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Sep 25, 2019:</span>
                admin updated task
                <a href="#">tssssss...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Sep 25, 2019:</span>
                admin updated task
                <a href="#">tssssss...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Sep 19, 2019:</span>
                admin updated task
                <a href="#">tssssss...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Sep 19, 2019:</span>
                admin updated task
                <a href="#">tssssss...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Sep 19, 2019:</span>
                admin updated task
                <a href="#">tssssss...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Sep 19, 2019:</span>
                admin created task
                <a href="#">tssssss...</a>
              </li>
              <li class="list-group-item">
                <span style="color:#888;font-style:italic">Sep 19, 2019:</span>
                admin created client
                <a href="#">clint1</a>
              </li>
            </ul>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane p-0" id="projects">
            <table class="table table-condensed">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>projects</th>
                  <th>created at</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1.</td>
                  <td><router-link :to="'/project/' + 1">project 1</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
                <tr>
                  <td>2.</td>
                  <td><router-link :to="'/project/' + 1">project 1</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
                <tr>
                  <td>3.</td>
                  <td><router-link :to="'/project/' + 1">project 1</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
                <tr>
                  <td>4.</td>
                  <td><router-link :to="'/project/' + 1">project 1</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
              </tbody>
            </table>
            <ul class="pagination pagination-sm m-0 justify-content-center">
              <li class="page-item">
                <a class="page-link" href="#">«</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">2</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">3</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">»</a>
              </li>
            </ul>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane p-0" id="tickets">
            <table class="table table-condensed">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>tickets</th>
                  <th>created at</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1.</td>
                  <td><router-link :to="'/ticket/' + 1">tickets 1</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
                <tr>
                  <td>2.</td>
                  <td><router-link :to="'/ticket/' + 1">tickets 2</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
                <tr>
                  <td>3.</td>
                  <td><router-link :to="'/ticket/' + 1">tickets 3</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
                <tr>
                  <td>4.</td>
                  <td><router-link :to="'/ticket/' + 1">tickets 4</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
              </tbody>
            </table>
            <ul class="pagination pagination-sm m-0 justify-content-center">
              <li class="page-item">
                <a class="page-link" href="#">«</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">2</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">3</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">»</a>
              </li>
            </ul>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane p-0" id="tasks">
            <table class="table table-condensed">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>tasks</th>
                  <th>created at</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1.</td>
                  <td><router-link :to="'/task/' + 1">tasks 1</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
                <tr>
                  <td>2.</td>
                  <td><router-link :to="'/task/' + 1">tasks 2</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
                <tr>
                  <td>3.</td>
                  <td><router-link :to="'/task/' + 1">tasks 3</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
                <tr>
                  <td>4.</td>
                  <td><router-link :to="'/task/' + 1">tasks 4</router-link></td>
                  <td>
                      9/11/2019 10:00
                  </td>
                </tr>
              </tbody>
            </table>
            <ul class="pagination pagination-sm m-0 justify-content-center">
              <li class="page-item">
                <a class="page-link" href="#">«</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">2</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">3</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">»</a>
              </li>
            </ul>
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.card-body -->
      <!-- /.card-header -->
    </div>
    <!-- /.nav-tabs-custom -->
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>