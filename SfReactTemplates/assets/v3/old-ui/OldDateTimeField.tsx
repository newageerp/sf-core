import React, { useEffect, useState, useRef } from 'react'
import DatePicker from 'react-datepicker'
import moment from 'moment'
import 'react-datepicker/dist/react-datepicker.css'
import { InputFieldDefClass } from './OldInputField'

interface Props {
  value?: string
  onChange: (val: string) => void
  required?: boolean
  className?: string
}

export default function OldDateTimeField(props: Props) {
  const [valDate, setValDate] = useState(
    props.value ? moment(props.value, 'YYYY-MM-DD HH:mm').toDate() : undefined
  )
  const inputRef = useRef(null)

  useEffect(() => {
    setValDate(
      props.value ? moment(props.value, 'YYYY-MM-DD HH:mm').toDate() : undefined
    )
  }, [props.value])

  useEffect(() => {
    const _value = valDate ? moment(valDate).format('YYYY-MM-DD HH:mm') : ''
    const _valueToComp = props.value
      ? moment(props.value, 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD HH:mm')
      : ''

    if (_value !== _valueToComp) {
      props.onChange(_value)
    }
  }, [valDate])

  const className = [...InputFieldDefClass]
  if (props.required) {
    if (!valDate) {
      className.push('required-error')
    }
  }
  if (props.className) {
    className.push(props.className)
  }

  const onIconClick = () => {
    // console.log('inputRef', inputRef)
    if (inputRef && inputRef.current) {
      // @ts-ignore
      inputRef.current.setFocus(true)
    }
  }

  return (
    <div className={'flex gap-2 items-center'}>
      <div>
        <DatePicker
          dateFormat={"yyyy-MM-dd HH:mm"}
          showTimeSelect={true}
          className={className.join(' ')}
          selected={valDate}
          onChange={(date: Date) => {
            if (date) {
              setValDate(date)
            }
          }}
          ref={inputRef}
          calendarStartDay={1}
        />
      </div>
      <button onClick={onIconClick}>
        <i className={'fad fa-calendar-week'} />
      </button>
    </div>
  )
}
