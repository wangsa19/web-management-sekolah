<x-filament-panels::page>
    @if ($this->hasInfolist())
        {{ $this->infolist }}
        <div id="qrcode" style="width: 100px; height: 100px; margin-top: 15px;"></div>
    @else
        {{ $this->form }}
    @endif
    {{--  
    @if (count($relationManagers = $this->getRelationManagers()))
        <x-filament-panels::resources.relation-managers
            :active-manager="$this->activeRelationManager"
            :managers="$relationManagers"
            :owner-record="$record"
            :page-class="static::class"
        />
    @endif --}}
</x-filament-panels::page>
<script src="{{ asset('js/qrcode-js/qrcode.js') }}"></script>
<script>
    let qrcode = new QRCode(document.getElementById("qrcode"), {
        width: 100,
        height: 100,
    });

    function makeCode() {
        let elText = "halooo";
        qrcode.makeCode(elText);
    }

    makeCode();
</script>
