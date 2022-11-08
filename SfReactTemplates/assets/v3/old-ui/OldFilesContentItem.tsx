import React, { Fragment, useState, useEffect } from 'react'
import { useTranslation } from 'react-i18next'
import Button, { ButtonBgColor } from './OldButton'
import EmailForm from './OldEmailForm'
import Popup from './OldPopup'
import FilePopup, { IFilePopupItem } from './OldFilePopup'
import { OpenApi } from '@newageerp/nae-react-auth-wrapper'

interface Props {
  f: any
  reloadData: () => void
  otherFiles?: any[]
  readOnly?: boolean
  type: string
  parentSchema: string
  parentElementId: number
  fileActions: string[],
  mailExtraData?: any
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

export const getLinkForFolder = (folder: string): string => {
  return (
    window.location.origin +
    '/app/nae-core/files/download-zip?f=' +
    encodeURIComponent(
      JSON.stringify({
        folder: folder
      })
    ) +
    '&token=' +
    window.localStorage.getItem('token')
  )
}

const moduleName = 'file'

export default function OldFilesContentItem(props: Props) {
  const { f, reloadData } = props
  const { t } = useTranslation()

  const [removeData] = OpenApi.useURequest('NAEfileRemove')
  const doDelete = (f: IFilePopupItem) => {
    removeData({ path: f.path }).then(() => {
      props.reloadData()
      togglePreviewPopup()
    })
  }

  const [previewPopup, setPreviewPopup] = useState(0)
  const togglePreviewPopup = () => setPreviewPopup(previewPopup > 0 ? 0 : 1)

  const [emailPopup, setEmailPopup] = useState(false)
  const toggleEmailPopup = () => setEmailPopup(!emailPopup)

  const [fileToSend, setFileToSend] = useState<IFilePopupItem | null>(null)

  const [saveFileData] = OpenApi.useUSave(moduleName)
  const toggleChecked = () => {
    saveFileData({
      appproved: !f.appproved
    }, f.id).then(() => {
      reloadData()
    })
  }

  const fileName: string = f.filename
  const filePath: string = getLinkForFile(f)

  const doDownload = () => {
    window.open(filePath)
  }

  const actions: any = {
    download: (
      <Button
        bgColor={ButtonBgColor.blue}
        brightness={100}
        icon={'fal fa-download'}
        title={t('Download')}
        onClick={() => doDownload()}
      />
    ),
    preview: (
      <Button
        bgColor={ButtonBgColor.blue}
        brightness={100}
        icon={'fal fa-eye'}
        title={t('Preview')}
        onClick={() => setPreviewPopup(1)}
      />
    ),
    send: (
      <Button
        bgColor={ButtonBgColor.blue}
        brightness={100}
        icon={'fal fa-paper-plane'}
        title={t('Send')}
        onClick={toggleEmailPopup}
      />
    ),
    print: (
      <Button
        bgColor={ButtonBgColor.blue}
        brightness={100}
        icon={'fal fa-print'}
        title={t('Spausdinti')}
        onClick={() => setPreviewPopup(2)}
      />
    ),
    check: (
      <Button
        bgColor={f.appproved ? ButtonBgColor.green : ButtonBgColor.blue}
        brightness={100}
        icon={'fal fa-check-double'}
        title={t('Patvirtinti')}
        onClick={toggleChecked}
      />
    )
  }

  useEffect(() => {
    if (!emailPopup) {
      setFileToSend(null)
    }
  }, [emailPopup])

  return (
    <Fragment>
      <div className={'flex items-center gap-2'}>
        <div className={'flex-grow'}>
          <button
            className={
              (f.deleted ? 'text-danger' : 'text-secondary') +
              ' text-sm hover:underline text-left break-all	'
            }
            onClick={togglePreviewPopup}
          >
            {fileName}
          </button>
        </div>

        <div className={'grid gap-1 grid-flow-col'}>
          {props.fileActions.map((action: string) => {
            if (!!actions[action]) {
              return (
                <Fragment key={'f-' + f.id + '-' + action}>
                  {actions[action]}
                </Fragment>
              )
            }
            return <Fragment />
          })}
        </div>
      </div>

      <FilePopup
        visible={previewPopup > 0}
        printOnLoad={previewPopup === 2}
        toggleVisible={togglePreviewPopup}
        onRemove={props.readOnly ? undefined : doDelete}
        otherFiles={props.otherFiles
          ?.filter((f) => !f.deleted)
          .map((f) => {
            return {
              id: f.id,
              name: f.filename,
              link: getLinkForFile(f),
              type: f.extension,
              path: f.path
            }
          })}
        file={{
          id: f.id,
          name: f.filename,
          link: getLinkForFile(f),
          type: f.extension,
          path: f.path
        }}
        onEmailClick={(f) => {
          setFileToSend(f)
          toggleEmailPopup()
        }}
      />

      <Popup visible={emailPopup} toggleVisible={toggleEmailPopup}>
        <EmailForm
          extraData={{
            id: fileToSend ? fileToSend.id : f.id,
            schema: moduleName,
            template: props.type,
            parentSchema: props.parentSchema,
            parentElementId: props.parentElementId,
            ...props.mailExtraData
          }}
          files={[
            fileToSend
              ? {
                name: fileToSend.name,
                link: fileToSend.link
              }
              : {
                name: fileName,
                link: filePath
              }
          ]}
          onSend={toggleEmailPopup}
        />
      </Popup>
    </Fragment>
  )
}
