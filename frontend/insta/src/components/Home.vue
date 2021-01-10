<template>
  <div>
    <p align="center" v-if="error">{{ error }}</p>
    <div class="home_wrapper">
      <div v-if="contacts.length > 1" class="home_contacts">
        <ul>
          <li v-for="contact in contacts" :key="contact.id">
            <span style="font-size: 32pt; color: green; cursor: pointer" v-on:click="add(contact)">
              <b>+</b>
            </span>
            {{ contact.name }}
            -
            <span><b>{{ contact.phone }}</b></span>
          </li>
        </ul>
      </div>

      <div class="home_contacts">
        <p>Ваши конаткты:</p>
        <ul>
          <li v-for="contact in mycontacts" :key="contact.id">
            {{ contact.name }}
            -
            <span><b>{{ contact.phone }}</b></span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Home",
  data() {
    return {
      error : null,
      contacts : [],
      mycontacts : []
    }
  },
  methods: {
    add : function(contact) {
      this.$set(
        this.mycontacts,
        this.mycontacts.length,
        contact
      );
      fetch(
        process.env.baseUrl + '/contact_add',
        {
          method : 'POST',
          headers : {
            'Content-Type' : 'application/json',
            'Authorization' : localStorage.getItem('jwt')
          },
          body : JSON.stringify({
            'contact_id' : contact.id
          })
        }
      ).then( response => response.json() )
        .then(
          (data) => {
            if ( data.error ) throw data.error;
            else {
              console.log(data)
            }
          }
        )
      .catch(
        (e) => {
          this.error = e;
        }
      );
    },

    get_user_contacts : function() {
      fetch(
        process.env.baseUrl + '/get_user_contacts',
        {
          method : 'GET',
          headers : {
            'Authorization' : localStorage.getItem('jwt')
          }
        }
      ).then( response => response.json() )
        .then(
          (data) => {
            if ( data.error ) throw data.error;
            else {
              this.mycontacts = data;
            }
          }
        )
      .catch(
        (e) => {
          this.error = e;
        }
      )
    },

    get_contacts : function() {
      fetch(
        process.env.baseUrl + '/contacts',
        {
          method : 'GET',
          headers : {
            'Authorization' : localStorage.getItem('jwt'),
            'Content-Type' : 'application/json'
          }
        }
      ).then( response => response.json() )
        .then(
          (data) => {
            if ( data.error ) throw data.error;
            else {
              this.contacts = data;
            }
          }
        )
      .catch(
        (e) => {
          this.error = e;
        }
      )
    }
  },
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
                else {
                  console.log('Logged in!');
                  this.get_contacts();
                  this.get_user_contacts();
                }
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
  .home_wrapper {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    width: 70%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  .home_contacts {
    width: 400px;
    border: 2px solid red;
    border-radius: 10px;
    padding: 25px;
  }

  ul {
    list-style-type: none;
  }
</style>
