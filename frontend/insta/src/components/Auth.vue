<template>
  <div class="auth_wrapper">
    <div class="auth_login">
      <input type="text" placeholder="login" />
      <input type="password" placeholder="password" />
      <input type="submit" value="Войти" />
    </div>
    <div class="auth_login">
      <input type="text" placeholder="login" />
      <input type="password" placeholder="password" />
      <input type="submit" value="Регистрация" />
    </div>
  </div>
</template>

<script>
export default {
  name: "Auth"
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
