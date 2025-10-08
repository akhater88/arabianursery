@props([
    'seasons' => collect(),
    'selected' => null,
    'label' => 'الموسم',
    'name' => 'season_id',
    'id' => 'season-id',
    'includePlaceholder' => true,
    'required' => false,
])

<div {{ $attributes->class(['col-12', 'col-sm-4']) }}>
    <label for="{{ $id }}">{{ $label }}</label>
    <select
        class="form-control select2"
        id="{{ $id }}"
        name="{{ $name }}"
        style="width: 100%;"
        @if($required) required @endif
    >
        @if($includePlaceholder)
            <option value="">اختر موسم</option>
        @endif
        @foreach($seasons as $season)
            <option value="{{ $season->id }}" @selected($selected == $season->id)>
                {{ $season->name }}
                ({{ optional($season->start_date)->format('Y-m-d') }} - {{ optional($season->end_date)->format('Y-m-d') }})
            </option>
        @endforeach
    </select>
</div>
