import React from 'react'
import FileField from './OldFileField'

interface Props {
  property: any
  val: any
  onChange: (val: any) => void
}

export default function OldFileFieldMultiple(props: Props) {
  const files = props.val ? props.val : []

  const changeFile = (f: any, fIndex: number) => {
    if (f && f.filename) {
      props.onChange(
        files.map((_f: any, _index: number) => {
          if (_index === fIndex) {
            return f
          }
          return _f
        })
      )
    } else {
      props.onChange(
        files.filter((_f: any, _index: number) => _index !== fIndex)
      )
    }
  }

  const addFile = (f: any) => {
    props.onChange([...files, ...f])
  }

  return (
    <div
      className={
        'tw3-flex tw3-flex-col tw3-gap-1 tw3-items-center tw3-w-96 tw3-rounded-md tw3-border tw3-border-gray-300 tw3-px-2 tw3-py-1'
      }
    >
      {files.map((f: any, fIndex: number) => {
        return (
          <FileField
            width={'tw3-w-full'}
            key={'file-' + fIndex}
            val={f}
            onChange={(f: any) => changeFile(f, fIndex)}
            property={props.property}
          />
        )
      })}
      <FileField
        width={'tw3-w-full'}
        key={'file-new'}
        val={[]}
        onChange={addFile}
        property={props.property}
        allowMultiple={true}
      />
    </div>
  )
}
