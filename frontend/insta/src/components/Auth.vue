<template>
  <div class="auth_wrapper">
    <div class="auth_login">
      <input type="text" placeholder="login" v-model="sign_login" />
      <input type="password" placeholder="password" v-model="sign_password" />
      <input type="submit" value="Войти" v-bind:disabled="!(sign_password && sign_login)" v-on:click="login" />
    </div>

    <div class="auth_login">
      <input type="text" placeholder="login" v-model="register_login" />
      <input type="password" placeholder="password" v-model="register_password" />
      <input type="submit" value="Регистрация" v-bind:disabled="!(register_login && register_password)" v-on:click="register" />
    </div>
  </div>
</template>

<script>
export default {
  name: "Auth",
  data() {
    return {
      sign_login : null,
      sign_password : null,

      register_login : null,
      register_password : null
    }
  },

  mounted() {
    let jwt = localStorage.getItem('jwt');
    if ( jwt ) this.$router.push('/home');
  },

  methods: {
    login : function() {
      fetch(
        process.env.baseUrl + '/auth',
        {
          method : 'POST',
          headers : {
            'Content-Type' : 'application/json'
          },
          body : JSON.stringify({
            'username' : this.sign_login,
            'password' : this.sign_password
          })
        }
      ).then( response => response.json() )
        .then(
          (data) => {
            if ( data.error ) throw data.error;
            else {
              localStorage.setItem('jwt', data.jwt);
              this.$router.push('/home');
            }
          }
        )
      .catch(
        (e) => {
          console.error( e );
        }
      )
    },

    register : function() {
      fetch(
        process.env.baseUrl + '/register',
        {
          method : 'POST',
          headers : {
            'Content-Type' : 'application/json'
          },
          body : JSON.stringify({
            'username' : this.register_login,
            'password' : this.register_password
          })
        }
      ).then( response => response.json() )
        .then(
          ( data ) => {
            console.log(data);
          }
        )
    }
  }
}
</script>

<style scoped>
  .auth_login > input {
    margin-top: 15px;
    text-align: center;
  }

  .auth_login {
    width: 200px;
    height: auto;
    border: 2px solid grey;
    border-radius: 10px;
    padding: 25px;
    text-align: center;
  }

  .auth_wrapper {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    width: 70%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
</style>
