@include('calendario::paineldiretoria.events._wizard-form', [
    'event' => $event,
    'churches' => $churches,
    'discountRule' => $discountRule ?? null,
])
