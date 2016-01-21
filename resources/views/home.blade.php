@extends('app')

@section('content')
    <div id="loading-screen" class="hidden">
        <div id="load-content"><i class="fa fa-spin fa-spinner"></i></div>
    </div>

    <div id="context-menu" class="hidden">
        Adicionar novo ponto
    </div>
    <div class="row">
        <div id="topbar" class="col-xs-12">
            <div class="input-group">
                <input id="search-content" type="text" class="form-control">
                <span id="search-button" class="input-group-addon"><i class="fa fa-search"></i></span>
            </div>
        </div>
    </div>
    <div id="map">
        <div id="legenda">
            <div class="col-xs-4">
                <span class="fa-stack fa-lg">
                    <i style="color:white; font-size: 1.2em;" class="fa fa-circle fa-stack-1x"></i>
                    <i style="color:blue" class="fa fa-circle fa-stack-1x"></i>
                </span>
                Locais para plantio
            </div>
            <div class="col-xs-4">
                <span class="fa-stack fa-lg">
                    <i style="color:white; font-size: 1.2em;" class="fa fa-circle fa-stack-1x"></i>
                    <i style="color:green" class="fa fa-circle fa-stack-1x"></i>
                </span>
                Vegetação
            </div>
            <div class="col-xs-4">
                <span class="fa-stack fa-lg">
                    <i style="color:white; font-size: 1.2em;" class="fa fa-circle fa-stack-1x"></i>
                    <i style="color:red" class="fa fa-circle fa-stack-1x"></i>
                </span>
                Podas drásticas
            </div>
        </div>
    </div>
    <div class="row">
        <div id="sidebar" class="sidebar-hidden">
            <div id="user-login">
                <a href="{{$url}}">
                    {{$login}}
                    <i class="fa fa-user"></i>
                </a>
            </div>
            <div class="col-xs-12">
                <form id="form-insert" class="fieldset-form">
                    <fieldset>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        <input type="hidden" name="address" id="address">
                        <input type="hidden" name="city" id="city">
                        <input type="hidden" name="state" id="state">
                        <input type="hidden" name="country" id="country">

                        <legend>Informações sobre o ponto</legend>
                        <div class="form-group">
                            <div class="radio">
                                <input type="radio" name="type" value="LOCAL_PLANTIO">
                                <label for="radio-plantio">
                                    Local para plantio
                                </label>
                            </div>
                            <div class="radio">
                                <input type="radio" name="type" value="VEGETACAO">
                                <label for="radio-vegetacao">
                                    Vegetação
                                </label>
                            </div>
                            <div class="radio">
                                <input type="radio" name="type" value="PODA_DRASTICA">
                                <label for="radio-poda">
                                    Poda drástica
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Imagem</label>
                            <br>
                            <input type="file" accept="image/*" name="image" id="">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Espécie</label>
                            <br>
                            <input type="text" name="species" id="" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Observações</label>
                            <textarea name="comments" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="button" class="btn btn-default btn-fechar">Fechar</button>
                        <button id="btn-salvar" type="submit" class="btn btn-success">Salvar</button>
                    </fieldset>
                </form>
                <form id="form-show">
                    <fieldset>
                        <legend id="show-tipo"></legend>
                        <img id="show-imagem" src="">
                        <div class="form-group">
                            <label id="show-endereco" class="form-label"></label>
                        </div>
                        <div id="show-especie" class="form-group">
                            <label class="form-label">Espécie: <b></b></label>
                        </div>
                        <div id="show-observacoes" class="form-group">
                            <label class="form-label">Observações: <span></span></label>
                        </div>
                        <button type="button" class="btn btn-default btn-fechar">Fechar</button>
                        <button id="show-deletar" type="button" class="btn btn-danger">Deletar</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection