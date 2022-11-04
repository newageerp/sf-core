import React, { Fragment, useState } from 'react'
import Button, { ButtonBgColor, ButtonSize } from './OldButton'
import FilePopup from './OldFilePopup'


interface Props {
  file: any
  width?: string
  otherFiles?: any[]
}

export default function OldFileFieldRo(props: Props) {
  const { file } = props
  const [showFilePoup, setShowFilePopup] = useState(false)
  const toggleShowFilePopup = () => setShowFilePopup(!showFilePoup)

  let ext = ''
  if (file && file.filename) {
    const _name = file.filename.split('.')
    ext = _name[_name.length - 1]
  }

  return (
    <div
      className={`flex gap-2 items-center ${
        props.width ? props.width : 'w-96'
      }`}
    >
      <Fragment>
        <p className={'flex-grow'}>{file.filename}</p>

        <Button
          size={ButtonSize.sm}
          bgColor={ButtonBgColor.nsecondary}
          brightness={50}
          icon={'fal fa-eye'}
          onClick={toggleShowFilePopup}
        ></Button>

        <Button
          size={ButtonSize.sm}
          bgColor={ButtonBgColor.nsecondary}
          brightness={50}
          icon={'fal fa-download'}
          onClick={() => {
            const link = getLinkForFile(file)
            window.open(link, '_blank')
          }}
        ></Button>
      </Fragment>

      <FilePopup
        visible={showFilePoup}
        printOnLoad={false}
        toggleVisible={toggleShowFilePopup}
        file={{
          name: file.filename,
          link: getLinkForFile(file),
          type: ext,
          path: file.path,
          id: props.otherFiles ? props.otherFiles.indexOf(file) : 0
        }}
        otherFiles={
          props.otherFiles
            ? props.otherFiles.map((file, fIndex: number) => ({
                name: file.filename,
                link: getLinkForFile(file),
                type: ext,
                path: file.path,
                id: fIndex
              }))
            : undefined
        }
      />
    </div>
  )
}


export const getLinkForFile = (f: any): string => {
    return (
      window.location.origin +
      '/app/nae-core/files/download?f=' +
      encodeURIComponent(
        JSON.stringify({
          path: f.path,
          name: f.filename
        })
      ) +
      '&token=' +
      window.localStorage.getItem('token')
    )
  }