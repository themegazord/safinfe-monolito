<div>
  @if(session('erro'))
    <div class="alert alert-danger alert-dismissible fade show w-25 position-absolute top-0 end-0 m-3" role="alert">
      {{ session('erro')}}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if(session('alerta'))
    <div class="alert alert-warning alert-dismissible fade show w-25 position-absolute top-0 end-0 m-3" role="alert">
      {{ session('alerta')}}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if(session('sucesso'))
    <div class="alert alert-success alert-dismissible fade show w-25 position-absolute top-0 end-0 m-3" role="alert">
      {{ session('sucesso')}}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
</div>
