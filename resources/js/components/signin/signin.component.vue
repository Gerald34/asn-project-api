<template>
    <div>
        <div class="signin">
            <div class="row">

                <div class="col-lg-9">
                    <div class="row">
                        <div class="background"></div>
                        <div class="col-lg-8 hero text-light ml-5">
                            <img src="http://images.dev.coppertable.co.za/main@3x.svg" class="img-fluid logo"/>
                            <h3>Amatuer Sports Network</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam imperdiet fermentum facilisis. Duis massa ex, finibus vitae consectetur egestas, finibus quis eros.</p>
                            <h4>Technology in sports...</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam imperdiet fermentum facilisis. Duis massa ex, finibus vitae consectetur egestas, finibus quis eros.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 signinForm">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <h5>Account management</h5>
                        </div>

                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <input v-model="email" type="email" class="form-control" placeholder="Email Address">
                            </div>
                        </div>

                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <input v-model="password" type="password" class="form-control" placeholder="Password">
                            </div>
                        </div>

                        <div class="col-lg-12 mb-2">
                            <button @click="signin()" :disabled="!isValid" class="btn">
                                Sign in <i class="fas fa-sign-in-alt"></i>
                            </button>
                        </div>

                        <div class="col-lg-12 mb-2">
                            <a href="#">Forgot Password?</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'signin',
        data() {
            return {
                email: '',
                password: ''
            };
        },
        mounted() {
            // Init
        },
        methods: {
            signin() {
                const data = { email: this.email, password: this.password };
                console.log(data);
                axios.post('/api/administrator/authentication/signin', data)
                    .then((response) => {
                        toastr.success('Have fun storming the castle!', 'Miracle Max Says');
                        console.log(response);
                    }).catch(error => { toastr.error(error, 'Error') });
            }
        },
        computed: {
            isValid() {
                return this.email !== '' && this.password !== '';
            }
        }
    }
</script>

<style>
    .btn.disabled, .btn:disabled {
        opacity: 0.65;
        background: #888;
    }

    .hero { margin-top: 25%; }
    .hero p { font-weight: 500; }
    .signin {
        margin-top: 0%;
        width: 100%;
        height: 100vh;
        margin-left: auto;
        margin-right: auto;
        display: block;
    }

    .logo {
        width: 80px;
        margin-bottom: 15px;
    }

    .signinForm {
        margin-top: 19%;
        padding-left: 30px;
        padding-right: 30px;
    }

    .form-control {
        display: block;
        width: 100%;
        height: auto;
        padding: 0.775rem 1.3rem;
        font-size: 16px;
        font-weight: 400;
        line-height: 1.6;
        color: rgba(73, 80, 87, 0.8);
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 2px;
    }

    .btn {
        width: 100%;
        background: #fb8b25;
        color: #f9f9f9;
        padding: 0.775rem 1.3rem;
        font-size: 14px;
        text-transform: uppercase;
        font-weight: 600;
        border-radius: 2px;
        -webkit-box-shadow: 0 8px 8px 0 rgba(0,0,0,0);
        box-shadow: 0 8px 8px 0 rgba(0,0,0,0);

        -webkit-transition: all 5ms ease-in-out;
        -moz-transition: all 5ms ease-in-out;
        -o-transition: all 5ms ease-in-out;
        transition: all 5ms ease-in-out;
    }

    .btn:hover {
        background: #f9f9f9;
        color: #fb8b25;
    }

    .signinForm h5 {
        color: slategray;
    }
</style>
