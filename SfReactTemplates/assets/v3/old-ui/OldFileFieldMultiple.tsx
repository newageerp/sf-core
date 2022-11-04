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
        'flex flex-col gap-1 items-center w-96 rounded-md border border-gray-300 px-2 py-1'
      }
    >
      {files.map((f: any, fIndex: number) => {
        return (
          <FileField
            width={'w-full'}
            key={'file-' + fIndex}
            val={f}
            onChange={(f: any) => changeFile(f, fIndex)}
            property={props.property}
          />
        )
      })}
      <FileField
        width={'w-full'}
        key={'file-new'}
        val={[]}
        onChange={addFile}
        property={props.property}
        allowMultiple={true}
      />
    </div>
  )
}
