<?php if (count($links) > 0): ?>
    <table class="table table-striped table-padded">
        <thead>
            <tr>
                <th class="col-sm-3">Site</th>
                <th class="col-sm-3">Link</th>
                <th class="col-sm-1">Vote</th>
                <th class="col-sm-1">Report</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($links as $link): ?>
                <tr>
                    <td><?php echo $link->domain ?></td>
                    <td><a target="_blank" href="<?php echo $link->wrapped_url ?>" class="btn btn-primary btn-sm">Watch Now</a></td>

                    <?php if ($link->user_link_report): ?>
                        <td>
                            <a href="#" class="btn btn-default btn-sm disabled"><span class="fa fa-arrow-up"></span></a>
                            <a href="#" class="btn btn-default btn-sm disabled"><span class="fa fa-arrow-down"></span></a>
                        </td>
                    <?php else: ?>
                        <td>
                            <a onclick="upvote(this);return false;" href="#" data-url="/link/upvote/{{$link->id}}" class="btn btn-default btn-sm"><span class="fa fa-arrow-up"></span></a>
                            <a onclick="downvote(this);return false;" href="#" data-url="/link/downvote/{{$link->id}}" class="btn btn-default btn-sm"><span class="fa fa-arrow-down"></span></a>
                        </td>
                    <?php endif ?>



                    <?php if ($link->user_link_report): ?>
                        <td>
                            <a href="#" class="btn btn-danger btn-sm disabled"><span class="fa fa-flag"></span></a>
                            <?php if (Helpers::hasAccess('super')): ?>
                                <a onclick="deleteLink(this);return false;" href="#" data-url="/link/delete/{{$link->id}}" class="btn btn-danger btn-sm"><span class="fa fa-remove">X</span></a>

                            <?php endif ?>
                        </td>
                    <?php else: ?>
                        <td>
                            <a onclick="reportLink(this);return false;" href="#" data-url="/link/report/{{$link->id}}" class="btn btn-danger btn-sm"><span class="fa fa-flag"></span></a>
                            <?php if (Helpers::hasAccess('super')): ?>
                                <a onclick="deleteLink(this);return false;" href="#" data-url="/link/delete/{{$link->id}}" class="btn btn-danger btn-sm"><span class="fa fa-remove">X</span></a>

                            <?php endif ?>
                        </td>

                    <?php endif ?>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
<?php else: ?>
    <div style="padding:50px;text-align:center;">
        <h3>No Links Available</h3>
    </div> 
<?php endif ?>
