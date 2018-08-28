<template>
  <div class="pagination" :ref="ref.pagination">

    <div class="first" @click="$emit('change', 1)" v-if="status === 'prev' && currentPage !== 1 && pageCount !== 0">1</div>

    <div class="prev" @click.stop="turnPrev"><i class="el-icon-arrow-left" /></div>

    <div class="pagers">
      <div v-for="pager in pagers"
        :key="pager"
        :class="{current: pager === currentPage}"
        @click="$emit('change', pager)">
        {{ pager }}
      </div>
    </div>

    <div class="next" @click="turnNext"><i class="el-icon-arrow-right" /></div>

    <div class="last" @click="$emit('change', pageCount)" v-if="status === 'next' && currentPage !== pageCount  && pageCount !== 0">{{ pageCount }}</div>
  </div>
</template>

<script>
export default {
  name: 'Pagination',
  props: ['total', 'pageSize', 'currentPage', 'pagerNum'],
  data () {
    return {
      // element dom ref
      ref: {
        pagination: `${Date() + Math.random()}`
      },
      // num letter width
      pagerNumWidth: 10,
      // pagers
      pagers: [],
      // turn to page status
      status: 'next'
    }
  },
  computed: {
    pageCount () {
      const { calcPageCount } = this

      return calcPageCount()
    }
  },
  watch: {
    currentPage () {
      const { refreshPagers } = this

      refreshPagers()
    }
  },
  methods: {
    /**
     * @description             init pagination
     * @return     {undefined}  no return
     */
    initPagers () {
      const { currentPage, checkPageIndexIsValid } = this

      if (!checkPageIndexIsValid(currentPage)) return

      const { pagerNum, calcCanPutPagerNum, calcPageCount, producePagers } = this

      // total page count
      const pageCount = calcPageCount()

      const pagersLen = pagerNum || calcCanPutPagerNum()

      if (!pagersLen) return

      this.pagers = producePagers(currentPage, pagersLen, pageCount)
    },
    /**
     * @description           check pageIndex weather valid
     * @return     {boolean}  valid -> ture
     */
    checkPageIndexIsValid (index) {
      const { calcPageCount } = this

      return index > 0 && index <= calcPageCount()
    },
    /**
     * @description          calc total page num
     * @return     {number}  pageCount
     */
    calcPageCount () {
      const { total, pageSize } = this

      return Math.ceil(total / pageSize)
    },
    /**
     * @description          calc pager btn num
     * @return     {number}  can put paers btn num
     */
    calcCanPutPagerNum () {
      const { ref: { pagination }, $refs } = this

      const { currentPage, pagerNumWidth } = this

      const currentPagerNumWidth = currentPage.toString().length * pagerNumWidth

      const spareSpace = $refs[pagination].clientWidth - (50 * 3 + 30)

      const pagerWidth = currentPagerNumWidth + 30

      const pagersNum = spareSpace / pagerWidth

      return pagersNum >= 1 ? pagersNum : false
    },
    /**
     * @description         produce pagers
     * @return     {array}  pagers
     */
    producePagers (currentPage, pagersLen, pageCount) {
      const pagers = [currentPage]

      for (let i = 0; i < pagersLen - 1; i++) {
        const first = pagers[0]
        const last = pagers[pagers.length - 1]
        if (i % 2 === 0) {
          if (last < pageCount) {
            pagers.push(last + 1)
          } else if (first > 1) {
            pagers.unshift(first - 1)
          }
        } else {
          if (first > 1) {
            pagers.unshift(first - 1)
          } else if (last < pageCount) {
            pagers.push(last + 1)
          }
        }
      }

      return pagers
    },
    /**
     * @description             turn to prev pagers
     * @return     {undefined}  no return
     */
    turnPrev () {
      const { pageCount, currentPage, pagers } = this

      if (!pageCount) return

      const pagersFirst = pagers[0]

      const turnIndex = 2 * pagersFirst - currentPage

      const index = (turnIndex < 1 ? 1 : turnIndex)

      if (index === currentPage) return

      this.$emit('change', index)
    },
    /**
     * @description             turn to next pagers
     * @return     {undefined}  no return
     */
    turnNext () {
      const { currentPage, pagers, pageCount } = this

      if (!pageCount) return

      const pagersLast = pagers[pagers.length - 1]

      const turnIndex = 2 * pagersLast - currentPage

      const index = (turnIndex > pageCount ? pageCount : turnIndex)

      if (index === currentPage) return

      this.$emit('change', index)
    },
    /**
     * @description             refresh pager btns
     * @return     {undefined}  no return
     */
    refreshPagers () {
      const { initPagers } = this

      const beforeRefreshFirstIndex = this.pagers[0]

      initPagers()

      const afterRefreshFirstIndex = this.pagers[0]

      if (beforeRefreshFirstIndex < afterRefreshFirstIndex) {
        this.status = 'next'
      } else if (beforeRefreshFirstIndex > afterRefreshFirstIndex) {
        this.status = 'prev'
      }
    }
  },
  mounted () {
    const { initPagers } = this

    initPagers()
  }
}
</script>

<style lang="less">
@import '../assets/style/color.less';

.pagination {
  display: flex;
  flex-direction: row;
  line-height: 40px;
  text-align: center;
  box-shadow: 0 0 3px @main-color;
  justify-content: center;

  .hover {
    &::after {
      content: '';
      display: block;
      position: absolute;
      width: 0px;
      height: 0px;
      background-color: #fff;
      border-radius: 50%;
      left: 50%;
      top: 50%;
      transform: translateX(-50%) translateY(-50%);
      transition: all 0.3s;
      z-index: -1;
    }

    &:hover {
      color: @main-color;
      text-shadow: 0 0 1px #fff,
      0 0 2px #fff,
      0 0 3px #fff,
      0 0 4px @main-color,
      0 0 5px @main-color;

      &::after {
        width: 25px;
        height: 25px;
        box-shadow: 0 0 3px @main-color;
      }
    }
  }

  .prev, .next {
    position: relative;
    flex: 0 0 auto;
    padding: 0 10px;
    color: @main-color;
    font-weight: bold;
    cursor: pointer;
    .hover;
  }

  .pagers {

    div {
      position: relative;
      padding: 0 15px;
      float: left;
      color: @main-color;
      cursor: pointer;
      .hover;
    }
  }

  .current {
    font-weight: bold;
    cursor: default;

    &:hover {
      &::after {
        width: 0px;
        height: 0px;
      }
    }
  }

  .first, .last {
    position: relative;
    padding: 0 10px;
    color: @main-color;
    cursor: pointer;
    .hover;
  }
}
</style>
