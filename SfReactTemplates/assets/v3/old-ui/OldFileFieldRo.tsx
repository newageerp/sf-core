import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle'
import React, { Fragment, useState } from 'react'
import { PopupFile } from '@newageerp/ui.popups.base.popup-file';
import { FilesWindow } from '@newageerp/ui.files.files.files-window';

interface Props {
  file: any
  width?: string
  otherFiles?: any[],
  short?: boolean
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

  let width = 'tw3-w-96 tw3-max-w-96';
  if (props.short) {
    width = 'tw3-w-auto';
  }
  if (props.width) {
    width = props.width;
  }

  return (
    <div
      className={`tw3-flex tw3-gap-2 tw3-items-center ${width}`}
      title={file.filename}
    >
      <Fragment>
        {!props.short &&
          <p className={'tw3-flex-grow tw3-truncate'}>{file.filename}</p>
        }

        <ToolbarButton
          iconName='eye'
          onClick={toggleShowFilePopup}
        />
        <ToolbarButton
          iconName='download'
          onClick={() => {
            const link = getLinkForFile(file)
            window.open(link, '_blank')
          }}
        />
      </Fragment>
      {showFilePoup &&
        <PopupFile onClose={toggleShowFilePopup}>
          <FilesWindow
            file={{
              title: file.filename,
              onView: {
                link: getLinkForFile(file),
                ext: ext,
                id: file.id,
              },
              onDownload: () => {
                const link = getLinkForFile(file)
                window.open(link, '_blank')
              }
            }}
            onBack={toggleShowFilePopup}
            otherFiles={props.otherFiles
              ? props.otherFiles.map((file, fIndex: number) => ({
                title: file.filename,
                onView: {
                  link: getLinkForFile(file),
                  ext: ext,
                  id: file.id,
                },
                onDownload: () => {
                  const link = getLinkForFile(file)
                  window.open(link, '_blank')
                }
              })
              )
              : undefined}
          />
        </PopupFile>
      }
    </div>
  )
}


export const getLinkForFile = (f: any): string => {
  if (f.path.indexOf('http://') === 0 || f.path.indexOf('https://') === 0) {
    return f.path;
  }
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