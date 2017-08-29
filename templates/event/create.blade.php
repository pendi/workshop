@extends('shared/create')

<?php use ROH\Util\Inflector; ?>

@section('back')
    <ul class="flat left">
        <li><a href="{{ f('controller.url', '/'.$_GET['cat'].'/listEvent') }}"><i class="xn xn-left-open"></i>{{ l('Back') }}</a></li>
    </ul>
@stop

@section('fields')
    <style>
        .btn:hover { background-color: #e6e6e6; }
        .btn { border: 1px solid #bfbfbf; }
    </style>

    <form method="post" id="createform" class="read">
        <h1>{{ l('Create New {0}', Inflector::humanize(f('controller')->getClass())) }}</h1>
        <div class="row">
            <div class="span-6 medium-12">
                <div class="row">
                    <label>Name*</label>
                    <select name="name" class="select-2">
                        <option value="">---</option>
                        <?php foreach ($dataUsers as $key => $user): ?>
                            <option value="<?php echo $user['first_name'].' '.$user['last_name'] ?>"><?php echo $user['first_name'].' '.$user['last_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <input type="text" style="display: none;" class="name-hidden" placeholder="Name">
                </div>
            </div>
            <?php $i = 0 ?>
            @foreach (f('controller')->schema() as $name => $field)
                @if (!$field['hidden'])
                    <div class="span-6 medium-12">
                        <div class="row {{ (f('notification.message', $name) AND $app->request->getMethod() !== 'GET') ? 'tooltip-text required' : ''}}">
                            {{ $field->label() }}
                            {{ $entry->format($name, 'input') }}
                            <span class="tooltip bottom">{{ f('notification.message', $name) }}</span>
                        </div>
                    </div>
                @endif
            @endforeach
            <div class="span-6 medium-12">
                <div class="row">
                    <label>Category</label>
                    <input type="text" disabled="disabled" value="<?php echo $category['name'] ?>">
                    <input type="hidden" name="category" value="<?php echo $category['$id'] ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="span-12 medium-12">
                <div class="row">
                    <div class="btn-group" role="group">
                      <button type="button" class="btn btn-employee">Employees</button>
                      <button type="button" class="btn btn-other">Other</button>
                    </div>
                </div>
            </div>
        </div>
        <input type="submit" value="Submit" class="hidden" />
    </form>

    <script>\
        $(".btn-other").on("click", function () {
            $(".name-hidden").css("display", 'block');
            $(".select2-container").css("display", 'none');
            $(".select-2").removeAttr( "name" );
            $(".name-hidden").attr( "name", "name" );
        });

        $(".btn-employee").on("click", function () {
            $(".name-hidden").css("display", 'none');
            $(".select2-container").css("display", 'block');
            $(".name-hidden").removeAttr( "name" );
            $(".select-2").attr( "name", "name" );
        });
    </script>
@stop
