@extends('shared/update')

<?php use ROH\Util\Inflector; ?>

@section('back')
    <ul class="flat left">
        <li><a href="{{ f('controller.url', '/'.$entry['category'].'/listEvent') }}"><i class="xn xn-left-open"></i>{{ l('Back') }}</a></li>
    </ul>
@stop

@section('fields')
    <form method="post" id="updateform" class="read">
        <h1>{{ l('Update {0}', array(Inflector::humanize(f('controller')->getClass())))}}</h1>
        <div class="row">
            <?php $i = 0; ?>
            @foreach (f('controller')->schema() as $name => $field)
                @if (!$field['hidden'])
                    <div class="span-6 medium-12">
                        <div class="row {{ (f('notification.message', $name) AND $app->request->getMethod() !== 'GET') ? 'tooltip-text required' : ''}}">
                            {{ $field->label() }}
                            {{ $entry->format($name, 'input') }}
                            <span class="tooltip bottom">{{ f('notification.message', $name) }}</span>
                        </div>
                    </div>
                    <?php if (++$i % 2 == 0) echo "</div><div class='row'>"; ?>
                @endif
            @endforeach
            <div class="span-6 medium-12">
                <div class="row">
                    <label>Category</label>
                    <input type="text" disabled="disabled" value="<?php echo $entry->format('category') ?>">
                </div>
            </div>
        </div>
    </form>
@stop