@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <div class="card">
                        <div class="card-body">
                            <div id="container-message"></div>
                        </div>
                        <div class="card-footer">
                            <input type="text" id="text-message" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module">

    async function config() {

        let headers = { headers: { Authorization: null} };

        let res = await axios.get(window.location.origin + '/token-session');

        headers
            .headers
            .Authorization = 'Bearer ' + res.data.token;

        return headers;
    }

    async function init(){

        const containerMessage = document.querySelector('#container-message');
        const txtMessage = document.querySelector('#text-message')
        let setup = await config();

        let res = await axios.get(window.location.origin + '/api/message', setup);

        for(let message of res.data) {

            let div = document.createElement('div');

            div.innerHTML = '<div class="alert alert-info">' + message.content + '</div>';

            containerMessage.appendChild(div);
        }

        txtMessage.addEventListener('keypress', send_message.bind({config: setup}));

        Echo.channel('message')
        .listen('MessageEvent', event => {
            let div = document.createElement('div');

                div.innerHTML = '<div class="alert alert-info">' + event.message.content + '</div>';

                containerMessage.appendChild(div);
        });

    }

    init();


    async function send_message(event) {

        let { config } = this;

        if(event.keyCode == 13) {

            let form = {
                content: event.target.value
            };

            await axios.post(window.location.origin + '/api/message', form, config);
        }
    }



</script>
@endsection
