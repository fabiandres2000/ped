@if ($paginas > 1)
    @php
        $primera = $actual - 5 > 1 ? $actual - 5 : 1;
        $ultima = $actual + 5 < $paginas ? $actual + 5 : $paginas;
    @endphp
    <div class="row">
        <div class="col-md-10">
            <ul class="pagination">
                <!--// flecha anterior-->
                @if ($actual != 1)
                    <li class="page-item ">
                        <a class="page-link"
                            href="{{ url('/ModuloE/GestionAsignaturas/?page=' . ($actual - 1) . '&txtbusqueda=' . $busqueda) }}"
                            aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                @endif

                <!--// si la primera del grupo no es la pagina 1, mostramos la 1 y los ...-->
                @if ($primera != 1)
                    <li class="page-item ">
                        <a class="page-link"
                            href="{{ url('/ModuloE/GestionAsignaturas/?page=1&txtbusqueda=' . $busqueda) }}"
                            aria-label="Previous">
                            <span aria-hidden="true">1</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">...</span>
                        </a>
                    </li>
                @endif
                <!--// mostramos la página actual, las 5 anteriores y las 5 posteriores-->
                @for ($i = $primera; $i <= $ultima; $i++)
                    @if ($actual == $i)
                        <li class="page-item active">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">{{ $actual }}</span>
                            </a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ url('/ModuloE/GestionAsignaturas/?page=' . $i . '&txtbusqueda=' . $busqueda) }}"
                                aria-label="Previous">
                                <span aria-hidden="true">{{ $i }}</span>
                            </a>
                        </li>
                    @endif
                @endfor

                <!--// si la ultima del grupo no es la ultima (lol), mostramos la ultima y los ...-->
                @if ($ultima != $paginas)
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">...</span>
                        </a>
                    </li>
                    <li class="page-item ">
                        <a class="page-link"
                            href="{{ url('/ModuloE/GestionAsignaturas/?page=' . $paginas . '&txtbusqueda=' . $busqueda) }}"
                            aria-label="Previous">
                            <span aria-hidden="true">{{ $paginas }}</span>
                        </a>
                    </li>
                @endif
                <!--// flecha siguiente-->
                @if ($actual != $paginas)
                    <li class="page-item {{ $actual == $paginas ? ' disabled' : '' }}">
                        <a class="page-link"
                            href="{{ url('/ModuloE/GestionAsignaturas/?page=' . ($paginas + 1) . '&txtbusqueda=' . $busqueda) }}">»</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
@endif
