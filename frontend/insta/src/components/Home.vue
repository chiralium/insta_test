<template>
  <div>restricted page</div>
</template>

<script>
export default {
  name: "Home",
  mounted() {
    let jwt = localStorage.getItem('jwt');
    if ( !jwt ) this.$router.push('/');
    else {
      fetch(
        process.env.baseUrl + '/home',
        {
          method : 'GET',
          headers : {
            'Authorization' : jwt,
            'Content-Type' : 'application/json'
          }
        }).then( response => response.json() )
            .then(
              ( data ) => {
                if ( data.error ) throw data.error;
                else console.log('Logged in!');
              }
          )
          .catch(
            (e) => {
              console.error(e);
              localStorage.clear();
              this.$router.push('/');
          }
        );
    }
  }
}
</script>

<style scoped>

</style>
