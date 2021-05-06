<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardBody>
            <CButton color="primary" @click="createRegion()">Create Region</CButton>
            <CAlert
                :show.sync="dismissCountDown"
                color="primary"
                fade
            >
              ({{dismissCountDown}}) {{ message }}
            </CAlert>
            <CDataTable
                hover
                :items="items"
                :fields="fields"
                :items-per-page="10"
                pagination
            >
              <template #author="{item}">
                <td>
                  <strong>{{item.name}}</strong>
                </td>
              </template>
              <template #title="{item}">
                <td>
                  <strong>{{item.description}}</strong>
                </td>
              </template>
              <template #edit="{item}">
                <td>
                  <CButton color="primary" @click="editRegion( item.id )">Edit</CButton>
                </td>
              </template>
              <template #delete="{item}">
                <td>
                  <CButton v-if="item.id" color="danger" @click="deleteRegion( item.id )">Delete</CButton>
                </td>
              </template>
            </CDataTable>
          </CCardBody>
        </CCard>
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Regions',
  data: () => {
    return {
      items: [],
      /*
      fields: [
        {key: 'author'},
        {key: 'title'},
        {key: 'content'},
        {key: 'applies_to_date'},
        {key: 'status'},
        {key: 'note_type'},
        {key: 'show'},
        {key: 'edit'},
        {key: 'delete'}
      ],
      */
      fields: ['id','name','description', 'edit', 'delete'],
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      you: null,
      message: '',
      showMessage: false,
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false
    }
  },
  computed: {
  },
  methods: {
    getRowCount (items) {
      return items.length
    },
    regionLink (id) {
      return `regions/${id.toString()}`
    },
    editLink (id) {
      return `regions/${id.toString()}/edit`
    },
    showRegion ( id ) {
      const regionLink = this.regionLink( id );
      this.$router.push({path: regionLink});
    },
    editRegion ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    deleteRegion ( id ) {
      let self = this;
      let regionId = id;
      axios.post(  this.$apiAdress + '/api/regions/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
          .then(function (response) {
            self.message = 'Successfully deleted note.';
            self.showAlert();
            self.getRegions();
          }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },
    createRegion () {
      this.$router.push({path: 'regions/create'});
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    getRegions (){
      let self = this;
      axios.get(  this.$apiAdress + '/api/regions?token=' + localStorage.getItem("api_token") )
          .then(function (response) {
            self.items = response.data;
          }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
    this.getRegions();
  }
}
</script>

<style scoped>
.card-body >>> table > tbody > tr > td {
  cursor: pointer;
}
</style>
