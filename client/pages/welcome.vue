<template>
  <div>
    <div class="container">
      <div class="py-1 text-center">
        <h2>Book listing</h2>
      </div>

      <div class="row">
        <v-client-table :columns="columns" :data="data" :options="options">
          <div slot="child_row" slot-scope="props">
            <a :href="`${baseURL}/photos/${props.row.image}`"><img :src="`${baseURL}/photos/thumbs/200x400/${props.row.image}`" alt=""></a>
          </div>
        </v-client-table>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  layout: 'default',
  head () {
    return { title: this.$t('home') }
  },

  data () {
    return {
      baseURL: 'http://bookslibrary',

      columns: ['title', 'isbn', 'description'],
      options: {
        headings: {
          name: this.$t('title'),
          isbn: this.$t('isbn'),
          description: this.$t('description')
        },
        sortable: ['title', 'isbn'],
        filterable: ['title', 'isbn'],
        perPage: 5,
        perPageValues: [
          5,
          10,
          25,
          50
        ]
      }
    }
  },

  computed: mapGetters({
    authenticated: 'auth/check'
  }),

  async asyncData () {
    const { data } = await axios.get('/books')

    return {
      data
    }
  }

}
</script>

<style>
  .VuePagination {
    text-align: center;
  }

  .vue-title {
    text-align: center;
    margin-bottom: 10px;
  }

  .vue-pagination-ad {
    text-align: center;
  }

  .glyphicon.glyphicon-eye-open {
    width: 16px;
    display: block;
    margin: 0 auto;
  }

  th:nth-child(3) {
    text-align: center;
  }

  .VueTables__child-row-toggler {
    width: 16px;
    height: 16px;
    line-height: 16px;
    display: block;
    margin: auto;
    text-align: center;
  }

  .VueTables__child-row-toggler--closed::before {
    content: "+";
    font-size: 30px ;
  }

  .VueTables__child-row-toggler--open::before {
    content: "-";
    font-size: 30px ;
  }

  .VueTables--client .row .col-md-12 {
    display: flex;
  }
</style>

<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}

.laravel {
  color: #2e495e;
}

.nuxt {
  color: #00c48d;
}

#app {
  width: 95%;
  margin: 0 auto;
}
</style>
