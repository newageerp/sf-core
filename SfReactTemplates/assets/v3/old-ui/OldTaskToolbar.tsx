import { OpenApi } from '@newageerp/nae-react-auth-wrapper'
import React, { useState, useEffect } from 'react'
import { useTranslation } from 'react-i18next'
import Badge, { BadgeBgColor } from './OldBadge'
import { useHistory } from 'react-router-dom';
import moment from 'moment';

interface Props {
  userId: number
}

export default function OldTaskToolbar(props: Props) {
    const history = useHistory();

  const [getData, getDataParams] = OpenApi.useUList('task', [])
  const [getDataLong, getDataParamsLong] = OpenApi.useUList('task', [])

  const [records, setRecords] = useState(0)
  const [recordsLong, setRecordsLong] = useState(0)
  const { t } = useTranslation()

  const goTo = (path: string) => {
    history.push(path);
  }

  const loadData = () => {
    const [, dateTo] = getTaskPeriodDates(0)

    const filters = [
      {
        and: [
          ['i.completed', '=', false, true],
          ['i.doer', '=', props.userId, true],
          ['i.dueDate', 'dlt', dateTo, true]
        ]
      }
    ]
    getData(filters, 1, 1)

    const filtersLong = [
      {
        and: [
          ['i.completed', '=', false, true],
          ['i.doer', '=', props.userId, true],
          ['i.longTerm', '=', 10, true]
        ]
      }
    ]
    getDataLong(filtersLong, 1, 1)
  }
  useEffect(loadData, [])

  useEffect(() => {
    if (getDataParams.data) {
      setRecords(getDataParams.data.records)
    }
  }, [getDataParams.data])

  useEffect(() => {
    if (getDataParamsLong.data) {
      setRecordsLong(getDataParamsLong.data.records)
    }
  }, [getDataParamsLong.data])

  useEffect(() => {
    const noteLoadInterval = window.setInterval(() => {
      loadData()
    }, 30 * 1000)
    return () => {
      window.clearInterval(noteLoadInterval)
    }
  }, [])

  return (
    <button
      title={t('Užduotys')}
      onClick={() => goTo('/c/apps/tasks')}
      className={'relative'}
    >
      <i className={'fad fa-tasks fa-fw text-nsecondary-100'} />
      {records > 0 && recordsLong > 0 && (
        <Badge
          bgColor={BadgeBgColor.red}
          size={'xs'}
          brightness={500}
          className={'absolute -top-2 -right-3'}
        >
          {records}/{recordsLong}
        </Badge>
      )}
      {records > 0 && recordsLong === 0 && (
        <Badge
          bgColor={BadgeBgColor.red}
          size={'xs'}
          brightness={500}
          className={'absolute -top-2 -right-3'}
        >
          {records}
        </Badge>
      )}
      {records === 0 && recordsLong > 0 && (
        <Badge
          bgColor={BadgeBgColor.gray}
          size={'xs'}
          brightness={500}
          className={'absolute -top-2 -right-3'}
        >
          {recordsLong}
        </Badge>
      )}
    </button>
  )
}

export const getTaskPeriodDates = (period: number) => {
    let dateTo = null
    let dateFrom = null
    if (period === 0) {
      dateTo = moment().add(1, 'days').format('YYYY-MM-DD')
    } else if (period === 1) {
      dateFrom = moment().add(1, 'days').format('YYYY-MM-DD')
      dateTo = moment().add(2, 'days').format('YYYY-MM-DD')
    } else if (period === 2) {
      dateFrom = moment().format('YYYY-MM-DD')
      dateTo = moment().add(8, 'days').format('YYYY-MM-DD')
    } else if (period === 3) {
      dateFrom = moment().format('YYYY-MM-DD')
      dateTo = moment().add(30, 'days').format('YYYY-MM-DD')
    } else if (period === 4) {
      dateTo = moment().format('YYYY-MM-DD')
    }
    return [dateFrom, dateTo]
  }