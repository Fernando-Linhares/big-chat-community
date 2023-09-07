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

<input type="hidden" name="user_id" value="{{ request()->user()->id }}">

<script type="module">

    async function config() {

        let headers = { headers: { Authorization: null} };

        let res = await axios.get(window.location.origin + '/token-session');

        headers
            .headers
            .Authorization = 'Bearer ' + res.data.token;

        return headers;
    }

    const containerMessage = document.querySelector('#container-message');

    async function init(){

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

            const user_id = document.querySelector('[name=user_id]').value;
            console.log(event.message.user_id != Number(user_id));
            if(event.message.user_id != Number(user_id)) {

                let div = document.createElement('div');

                div.innerHTML = '<div class="alert alert-success">' + event.message.content + '</div>';

                containerMessage.appendChild(div);
            }
        });
    }

    init();

    async function send_message(event) {

        let { config } = this;

        if(event.keyCode == 13) {

            let form = {
                content: event.target.value
            };

            const { data } = await axios.post(window.location.origin + '/api/message', form, config);

            event.target.value = '';

            let div = document.createElement('div');

            div.innerHTML = '<div class="alert alert-info">' + data.content + '</div>';

            containerMessage.appendChild(div);
        }
    }

</script>
@endsection
